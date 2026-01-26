@extends('layout.site.app')

@section('title', 'تفاصيل الطلب #' . $order->number_order )

@section('css')
<style>
    :root {
        --primary: #007aff;
        --primary-soft: #e5f1ff;
        --success: #34c759;
        --success-soft: #eafaf1;
        --warning: #ff9500;
        --warning-soft: #fff7eb;
        --error: #ff3b30;
        --dark: #1d1d1f;
        --gray-bg: #f5f5f7;
        --border: #d2d2d7;
    }

    body { background-color: var(--gray-bg); font-family: 'Cairo', sans-serif; color: var(--dark); }
    .details-wrapper { max-width: 1100px; margin: 40px auto; padding: 0 20px; }

    .glass-card {
        background: #ffffff; border-radius: 24px; padding: 25px;
        border: 1px solid #e5e5e7; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 24px;
    }

    .payment-status-bar {
        padding: 15px 20px; border-radius: 16px; display: flex;
        align-items: center; gap: 12px; margin-bottom: 20px; font-weight: 700;
    }
    .status-review { background: var(--primary-soft); color: var(--primary); border: 1px solid #bcd9ff; }
    .status-pending { background: var(--warning-soft); color: var(--warning); border: 1px solid #ffe1b5; }
    .status-confirmed { background: var(--success-soft); color: var(--success); border: 1px solid #b7f0d0; }

    .status-stepper { display: flex; justify-content: space-between; position: relative; }
    .status-stepper::before { content: ""; position: absolute; top: 15px; left: 0; right: 0; height: 2px; background: #e5e5e7; z-index: 1; }
    .step { position: relative; z-index: 2; text-align: center; flex: 1; }
    .step-icon {
        width: 32px; height: 32px; border-radius: 50%; background: #e5e5e7;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 8px;
        color: white; font-size: 14px; transition: 0.3s;
    }
    .step.active .step-icon { background: var(--primary); box-shadow: 0 0 0 5px var(--primary-soft); }
    .step.active .step-text { color: var(--primary); font-weight: 700; }
    .step-text { font-size: 12px; color: #86868b; }

    .invoice-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .invoice-table thead th { padding: 15px; color: #86868b; font-weight: 600; font-size: 13px; border-bottom: 1px solid #f2f2f2; }
    .invoice-table tbody td { padding: 15px; border-bottom: 1px solid #f8f8f8; }
    .prod-thumb { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; }

    .summary-section td { padding: 8px 15px; border: none; font-size: 14px; }
    .total-row { background: #fafafa; border-top: 2px solid #f2f2f2 !important; }
    .total-row td { padding: 20px 15px !important; font-size: 1.3rem !important; font-weight: 900; color: var(--primary); }

    .upload-area {
        border: 2px dashed var(--border); border-radius: 15px; padding: 20px;
        text-align: center; cursor: pointer; background: #fafafa; transition: 0.3s;
    }
    .upload-area:hover { border-color: var(--primary); background: var(--primary-soft); }
    .preview-img { max-width: 100%; border-radius: 10px; display: none; margin-top: 10px; }

    .btn-submit-invoice {
        background: var(--primary); color: white; border-radius: 12px;
        padding: 12px; width: 100%; border: none; font-weight: 600; margin-top: 15px;
    }

    .toast-custom {
        position: fixed; top: 25px; left: 50%; transform: translateX(-50%);
        color: white; padding: 12px 30px; border-radius: 50px;
        display: none; z-index: 9999; font-weight: 600; box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .toast-bg-success { background: var(--success); }
    .toast-bg-error { background: var(--error); }

    .coupon-badge {
        background: #fff0f0; color: #d70000; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; border: 1px dashed #ffbaba;
    }
</style>
@endsection

@section('content')
<div class="details-wrapper text-end" dir="rtl">
    <div class="toast-custom" id="generalToast"></div>

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="fw-bold mb-1" style="font-size: 28px;">تفاصيل الطلب</h1>
            <p class="text-muted m-0">رقم المرجع: <span class="text-dark fw-bold">#{{ $order->number_order }}</span></p>
        </div>
        <div class="text-start">
            <span class="badge" style="background: white; color: var(--dark); padding: 10px 18px; border-radius: 12px; border: 1px solid #e5e5e7;">
                {{ $order->created_at->format('Y/m/d') }}
            </span>
        </div>
    </div>

    {{-- حالة الدفع --}}
    @if($order->type_payment == 'wallet')
        @if(!$payment)
            <div class="payment-status-bar status-pending">
                <i class="fas fa-exclamation-circle"></i>
                <span>حالة الدفع: بانتظار رفع صورة التحويل لتأكيد طلبك</span>
            </div>
        @else
            <div class="payment-status-bar status-review">
                <i class="fas fa-history"></i>
                @if ($order->payment_status == 'unpaid')
                    <span>حالة الدفع: جاري مراجعة صورة التحويل </span>
                @else
                    <span>حالة الدفع: تم تأكيد دفعك بنجاح</span>
                @endif
            </div>
        @endif
    @else
        <div class="payment-status-bar status-confirmed">
            <i class="fas fa-truck"></i>
            <span>حالة الدفع: الدفع عند الاستلام (كاش)</span>
        </div>
    @endif

    {{-- التجهيز --}}
    <div class="glass-card">
        <div class="status-stepper">
            @php
                $steps = [
                    'pending' => ['label' => 'بانتظار التأكيد', 'icon' => 'fa-clock'],
                    'processing' => ['label' => 'التجهيز', 'icon' => 'fa-box'],
                    'delivering' => ['label' => 'الشحن', 'icon' => 'fa-truck'],
                    'completed' => ['label' => 'الاستلام', 'icon' => 'fa-check'],
                ];
                $reached = true;
            @endphp
            @foreach($steps as $key => $step)
                <div class="step {{ $reached ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas {{ $step['icon'] }}"></i></div>
                    <div class="step-text">{{ $step['label'] }}</div>
                </div>
                @if($key == $order->status) @php $reached = false; @endphp @endif
            @endforeach
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-{{ ($order->type_payment == 'wallet' && !$payment) ? '8' : '12' }}">
            <div class="glass-card p-0 overflow-hidden">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0">منتجات الفاتورة</h5>
                    <span class="text-muted small">عدد المنتجات: {{ $order->orderItems->count() }}</span>
                </div>
                <div class="table-responsive">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th class="text-start">المنتج</th>
                                <th class="text-center">الكمية</th>
                                <th class="text-start">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item->product->image_url }}" class="prod-thumb">
                                        <div>
                                            <div class="fw-bold" style="font-size: 14px;">{{ $item->product->title }}</div>
                                            <div class="text-muted small">سعر القطعة: ج.م {{ number_format($item->price, 2) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-start fw-bold text-dark">ج.م {{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="summary-section">
                            <tr>
                                <td colspan="2" class="text-start text-muted">المجموع الفرعي:</td>
                                <td class="text-start fw-bold">{{ number_format($order->subtotal, 2) }} ج.م</td>
                            </tr>

                            @if($order->couponUsage)
                            <tr>
                                <td colspan="2" class="text-start text-muted">
                                    خصم الكوبون <span class="coupon-badge mr-2">{{ $order->couponUsage->coupon->code }}</span> :
                                </td>
                                <td class="text-start fw-bold text-danger">- {{ number_format($order->couponUsage->value_discound, 2) }} ج.م</td>
                            </tr>
                            @endif

                            <tr>
                                <td colspan="2" class="text-start text-muted">مصاريف الشحن:</td>
                                <td class="text-start fw-bold text-success">+ {{ number_format($order->delivery_fee, 2) }} ج.م</td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="2" class="text-start">الإجمالي النهائي:</td>
                                <td class="text-start">{{ number_format($order->total_price, 2) }} ج.م</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($order->type_payment == 'wallet' && !$payment)
        <div class="col-lg-4">
            <div class="glass-card">
                <div class="d-flex align-items-center gap-2 mb-3 text-primary">
                    <i class="fas fa-wallet"></i>
                    <h6 class="m-0 fw-bold">بيانات المحفظة</h6>
                </div>

                <div style="background: var(--primary-soft); padding: 15px; border-radius: 15px; margin-bottom: 20px;">
                    <p class="small text-muted mb-2">رقم تحويل المبلغ:</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">01000899155</span>
                        <button class="btn btn-sm btn-primary" onclick="copyToClipboard('01000899155')">نسخ</button>
                    </div>
                </div>

                <form id="invoiceForm" enctype="multipart/form-data">
                    @csrf
                    <p class="small fw-bold mb-2">ارفع صورة التحويل:</p>
                    <div class="upload-area" onclick="document.getElementById('invoiceInput').click()">
                        <div id="uploadPlaceholder">
                            <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                            <p class="small text-muted m-0">اضغط لرفع الصورة</p>
                        </div>
                        <img id="imagePreview" class="preview-img">
                        <input type="file" id="invoiceInput" name="invoice" hidden accept="image/*">
                    </div>
                    <button type="submit" class="btn-submit-invoice" id="submitBtn">
                        <span class="btn-text">تأكيد الإرسال</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // تهيئة الـ Scripts
        $(document).on('click', '#openCart', function() {
            $('#sideCart, #cartOverlay').addClass('active');
            $('body').css('overflow', 'hidden');
        });

        $(document).on('click', '#closeCart, #cartOverlay', function() {
            $('#sideCart, #cartOverlay').removeClass('active');
            $('body').css('overflow', 'auto');
        });
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        showToast("✅ تم نسخ الرقم بنجاح", "success");
    }

    function showToast(msg, type = 'success') {
        const toast = $('#generalToast');
        toast.removeClass('toast-bg-success toast-bg-error');
        toast.addClass(type === 'success' ? 'toast-bg-success' : 'toast-bg-error');
        toast.text(msg).stop().fadeIn().delay(3000).fadeOut();
    }

    $('#invoiceInput').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#imagePreview').attr('src', event.target.result).show();
                $('#uploadPlaceholder').hide();
            }
            reader.readAsDataURL(file);
        }
    });

    $('#invoiceForm').submit(function(e) {
        e.preventDefault();
        let fileInput = document.getElementById('invoiceInput');
        if (fileInput.files.length === 0) {
            showToast("⚠️ يرجى اختيار صورة الفاتورة أولاً", "error");
            return false;
        }

        let formData = new FormData(this);
        let btn = $('#submitBtn');

        btn.prop('disabled', true).css('opacity', '0.7');
        btn.find('.btn-text').text('جاري التحقق والرفع...');
        btn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: "{{ route('order.upload.invoice', $order->id) }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res){
                showToast("✅ تم الرفع بنجاح", "success");
                setTimeout(() => location.reload(), 2000);
            },
            error: function(xhr) {
                resetBtn(btn);
                let errorMessage = "حدث خطأ أثناء الرفع";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showToast("❌ " + errorMessage, "error");
            }
        });
    });

    function resetBtn(btn) {
        btn.prop('disabled', false).css('opacity', '1');
        btn.find('.btn-text').text('تأكيد الإرسال');
        btn.find('.spinner-border').addClass('d-none');
    }
</script>
@endsection
