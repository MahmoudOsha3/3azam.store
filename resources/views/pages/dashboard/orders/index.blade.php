@extends('layout.dashboard.app')

@section('title' , 'شاشة الطلبات ')

@section('css')
<style>
    :root {
        --primary: #4f46e5;
        --secondary: #2d3436;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f1c40f;
        --info: #3498db;
        --sidebar-width: 260px;
    }

    .main-content { padding: 20px; transition: margin-right 0.3s ease; width: 100%; }

    @media (min-width: 1025px) {
        .main-content { margin-right: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); }
    }

    /* تنسيق الجدول */
    .table-responsive-wrapper {
        width: 100%; overflow-x: auto; background: white;
        border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .orders-table { width: 100%; border-collapse: collapse; min-width: 1000px; text-align: right; }
    .orders-table th { background: #f8fafc; padding: 15px; color: #64748b; border-bottom: 2px solid #edf2f7; }
    .orders-table td { padding: 15px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }

    /* تنسيق القوائم المنسدلة */
    .status-select {
        padding: 6px 10px; border-radius: 8px; border: 1px solid #e2e8f0;
        font-size: 13px; font-weight: 600; cursor: pointer; outline: none; transition: 0.3s;
    }
    .select-payment-paid { background: #dcfce7; color: #15803d; border-color: #b9f6ca; }
    .select-payment-unpaid { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
    .select-status-pending { background: #fef9c3; color: #a16207; }
    .select-status-completed { background: #dcfce7; color: #15803d; }

    /* --- الترقيم الاحترافي (Pagination) --- */
    .pagination-container {
        display: flex;
        justify-content: flex-start; /* ليكون جهة اليسار في نظام RTL */
        margin-top: 25px;
        direction: ltr; /* نجعل الأرقام تترتب من اليسار لليمين */
    }

    .pagination {
        display: flex;
        gap: 8px;
        list-style: none;
        padding: 0;
    }

    .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        color: #4b5563;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .page-item.active .page-link {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .page-item:not(.active):hover .page-link {
        background-color: #f9fafb;
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
    }

    .page-item.disabled .page-link {
        background-color: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }

    /* المودال الاحترافي */
    .modal-custom {
        position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(4px); z-index: 9999; display: none; align-items: center; justify-content: center;
    }
    .modal-content-card {
        background: white; width: 95%; max-width: 800px; border-radius: 20px;
        padding: 30px; max-height: 90vh; overflow-y: auto; position: relative;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    }
    .close-btn {
        position: absolute; top: 20px; left: 20px; background: #f1f5f9;
        border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 20px;
    }

    .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .items-table th { padding: 12px; border-bottom: 2px solid #f1f5f9; color: #64748b; text-align: right; }
    .items-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; }

    .summary-box {
        background: #f8fafc; border-radius: 12px; padding: 20px; margin-top: 25px;
        border: 1px solid #e2e8f0;
    }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: 600; }
    .total-row { font-size: 1.25rem; color: var(--primary); border-top: 2px dashed #cbd5e1; padding-top: 10px; margin-top: 10px; }

    .search-container {
        position: relative;
        width: 300px;
    }

    .search-input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        outline: none;
        transition: 0.3s;
        font-size: 14px;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <br>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin: 0; color: var(--secondary);">إدارة الطلبات</h2>
        <div style="color: #64748b; font-weight: bold;">إجمالي الطلبات: <span class="badge" style="color: black; background: #eee; padding: 5px 12px; border-radius: 8px;">{{ $orders->total() }}</span></div>
    </div>
    <div class="search-container">
        <form action="{{ route('orders.index') }}" method="get">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="order_number" id="orderSearch" value="{{ request('order_number') }}" class="search-input" placeholder="بحث برقم الطلب أو اسم العميل...">
        </form>
    </div><br>


    <div class="table-responsive-wrapper">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>إجمالي الطلب</th>
                    <th>حالة الطلب</th>
                    <th>حالة الدفع</th>
                    <th>طريقة الدفع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-weight: bold; color: var(--primary);">#{{ $order->number_order }}</td>
                    <td>
                        <div style="font-weight: 600;">{{ $order->user->name }}</div>
                        <div style="font-size: 11px; color: #94a3b8;">{{ $order->user->phone }}</div>
                    </td>
                    <td style="font-weight: bold;">{{ number_format($order->total_price, 2) }} ج.م</td>

                    <td>
                        <form action="{{ route('orders.update' , $order->id ) }}" method="POST">
                            @csrf @method('PUT')
                            <select name="status" class="status-select {{ $order->status == 'completed' ? 'select-status-completed' : 'select-status-pending' }}" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>جاري التنفيذ</option>
                                <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>جاري الشحن</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>تم التوصيل</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </form>
                    </td>

                    <td>
                        <form action="{{ route('order.payment.status' , $order->id ) }}" method="POST">
                            @csrf @method('PUT')
                            <select name="payment_status" class="status-select {{ $order->payment_status == 'paid' ? 'select-payment-paid' : 'select-payment-unpaid' }}" onchange="this.form.submit()">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>❌ قيد الانتظار</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✅ تم الدفع</option>
                            </select>
                        </form>
                    </td>

                    <td>
                        <span class="badge" style="background: #f1f5f9; color: #475569; padding: 5px 10px; border-radius: 6px;">
                            {{ $order->type_payment == 'cashOnDelivery' ? 'الدفع عند التوصيل' : 'دفع عبر المحفظة' }}
                        </span>
                    </td>
                    <td>
                        <button type="button" onclick="loadOrderDetails({{ $order->id }})"
                           style="background: var(--info); color: white; padding: 8px 15px; border:none; border-radius: 8px; cursor: pointer; font-size: 13px; transition: 0.3s;">
                            <i class="fas fa-eye"></i> التفاصيل
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">لا توجد طلبات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>
</main>

<div id="detailsModal" class="modal-custom">
    <div class="modal-content-card">
        <button class="close-btn" onclick="closeModal()">&times;</button>
        <h3 id="modalTitle" style="margin-top: 0; color: var(--secondary);">تفاصيل الطلب #</h3>
        <hr border="0" height="1" style="background:#eee">

        <div id="modalLoader" style="text-align:center; padding: 30px; display:none;">
            <i class="fas fa-circle-notch fa-spin fa-2x" style="color: var(--primary)"></i>
            <p>جاري جلب بيانات المنتجات...</p>
        </div>

        <div id="modalContent">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody id="itemsBody"></tbody>
            </table>

            <div class="summary-box">
                <div class="summary-row"><span>المجموع الفرعي:</span> <span id="subTotal">0.00</span></div>
                <div class="summary-row" style="color:#b91c1c"><span>كوبون خصم</span> <span id="discound">00.0</span></div>
                <div class="summary-row"><span>رسوم التوصيل:</span> <span id="deliveryFee">0.00</span></div>
                <div class="summary-row total-row"><span>الإجمالي النهائي:</span> <span id="grandTotal">0.00</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function loadOrderDetails(id) {
        const modal = $('#detailsModal');
        const content = $('#modalContent');
        const loader = $('#modalLoader');

        modal.css('display', 'flex').hide().fadeIn(300);
        content.hide();
        loader.show();

        $.ajax({
            url: `/admin/orders/${id}`,
            method: 'GET',
            success: function(res) {
                // ملاحظة: تأكد من شكل رد السيرفر (res.data أو res مباشرة)
                const order = res.data ? res.data : res;

                $('#modalTitle').text('تفاصيل الطلب #' + order.number_order);
                let rows = '';

                // التعامل مع أسماء الحقول بناءً على الـ Relation لديك
                order.order_items.forEach(item => {
                    const productName = item.product_name ? item.product_name : (item.product ? item.product.title : 'منتج غير معروف');
                    rows += `
                        <tr>
                            <td style="font-weight:600">${productName}</td>
                            <td>${parseFloat(item.price).toFixed(2)} ج.م</td>
                            <td style="text-align:center"><b>${item.quantity}</b></td>
                            <td style="font-weight:bold">${(item.price * item.quantity).toFixed(2)} ج.م</td>
                        </tr>
                    `;
                });

                $('#itemsBody').html(rows);
                $('#subTotal').text(parseFloat(order.subtotal || 0).toFixed(2) + ' ج.م');
                $('#deliveryFee').text(parseFloat(order.delivery_fee || 0).toFixed(2) + ' ج.م');
                $('#discound').text(parseFloat(-(order.coupon_usage?.value_discound ?? 0)).toFixed(2) + ' ج.م');
                $('#grandTotal').text(parseFloat(order.total_price || 0).toFixed(2) + ' ج.م');

                loader.hide();
                content.fadeIn(300);
            },
            error: function() {
                alert('فشل في جلب البيانات');
                closeModal();
            }
        });
    }

    function closeModal() {
        $('#detailsModal').fadeOut(200);
    }

    $(window).click(function(event) {
        if ($(event.target).hasClass('modal-custom')) closeModal();
    });

    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
</script>
@endsection
