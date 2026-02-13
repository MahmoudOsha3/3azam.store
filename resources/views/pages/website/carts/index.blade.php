@extends('layout.site.app')

@section('title', 'مراجعة الطلب | عظمه ستور')

@section('css')
<style>
    body { background-color: var(--bg); font-family: 'Inter', 'Cairo', sans-serif; }

    .checkout-wrapper { max-width: 650px; margin: 40px auto; padding: 0 15px; }

    /* كروت المنتجات الاحترافية */
    .section-label { font-size: 13px; font-weight: 700; color: #86868b; text-transform: uppercase; margin-bottom: 12px; display: block; text-align:right }

    .glass-section {
        background: #fff; border-radius: 20px; padding: 20px;
        border: 1px solid var(--card-border); margin-bottom: 25px;
    }

    .order-item {
        display: flex; align-items: center; gap: 15px; padding: 12px 0;
        border-bottom: 1px solid #f2f2f2;
    }
    .order-item:last-child { border-bottom: none; }

    .product-img { width: 65px; height: 65px; border-radius: 12px; object-fit: cover; background: #f9f9f9; }

    .product-details { flex-grow: 1; }
    .product-title { font-weight: 700; font-size: 14px; margin-bottom: 2px; color: #1d1d1f; }
    .product-meta { font-size: 12px; color: #86868b; }

    .product-price-qty {
        text-align: right; /* غيرناها من left إلى right */
        display: flex;
        flex-direction: column;
        align-items: flex-end; /* لضمان اصطفاف العناصر أقصى اليمين */
    }
    .unit-price { font-weight: 800; font-size: 14px; color: var(--primary); display: block; }
.qty-label {
    font-size: 11px;
    background: #f0f0f2;
    padding: 2px 8px;
    border-radius: 6px;
    color: #555;
    display: inline-block; /* لكي لا يأخذ السطر كاملاً */
    margin-top: 4px;
}
    /* خيارات الدفع الاحترافية */
    .payment-card {
        border: 2px solid #f2f2f2; border-radius: 18px; padding: 18px;
        cursor: pointer; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex; align-items: center; gap: 15px; margin-bottom: 12px;
    }
    .payment-card.active { border-color: var(--accent); background: #f5faff; }

    .payment-icon {
        width: 40px; height: 40px; background: #f9f9f9; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
    }
    .payment-card.active .payment-icon { background: #0066ff; color: #fff; }

    /* ملخص الحساب */
    .summary-line { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
    .total-line { border-top: 1px solid #eee; padding-top: 15px; margin-top: 10px; font-weight: 900; font-size: 1.2rem; }

    .btn-place-order {
        background: var(--primary); color: #fff; border: none; width: 100%;
        padding: 18px; border-radius: 16px; font-weight: 700; font-size: 1.1rem;
        transition: 0.3s; margin-top: 10px;
    }
    .btn-place-order:hover { opacity: 0.9; transform: translateY(-2px); }
    /* تصميم قسم الكوبون الاحترافي */
    .coupon-wrapper {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .coupon-input-group {
        position: relative;
        flex-grow: 1;
    }

    .coupon-input-group i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #86868b;
    }

    .coupon-field {
        width: 100%;
        padding: 12px 40px 12px 15px;
        border-radius: 14px;
        border: 1px dashed #d2d2d7;
        background: #fbfbfd;
        font-size: 14px;
        font-weight: 600;
        transition: 0.3s;
        outline: none;
    }

    .coupon-field:focus {
        border-color: var(--primary);
        background: #fff;
        border-style: solid;
    }

    .btn-apply-coupon {
        padding: 0 20px;
        border-radius: 14px;
        border: none;
        background: #1d1d1f;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        transition: 0.3s;
    }

    .btn-apply-coupon:hover {
        background: #000;
    }

    .coupon-success-msg {
        font-size: 12px;
        color: var(--success);
        margin-top: 8px;
        display: none; /* يظهر عند التفعيل */
        font-weight: 600;
    }
</style>
@endsection

@section('content')
    <div class="checkout-wrapper text-end" dir="rtl">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


    <h2 class="fw-bold mb-4" style="color: #1d1d1f;text-align:right">مراجعة الطلب</h2>

    <span class="section-label">المنتجات في سلتك</span>
    <span class="section-label">هل لديك كود خصم؟</span>
    <div class="glass-section shadow-sm mb-3">
    <form id="finalOrderForm" action="{{route('order.store')}}" method="POST">
        @csrf
        <div class="coupon-wrapper">
            <div class="coupon-input-group">
                <i class="fas fa-ticket-alt"></i>
                <input type="text" name="coupon_code" id="couponCode" class="coupon-field" placeholder="أدخل كود الكوبون هنا..." name="coupon">
            </div>
            {{-- <button type="button" class="btn-apply-coupon" >تطبيق</button> --}}
        </div>
        <div id="couponMessage" class="coupon-success-msg">
            <i class="fas fa-check-circle me-1"></i> تم تطبيق الخصم بنجاح!
        </div>
    </div>

    <div class="glass-section shadow-sm">
        <div class="summary-line">
            <span class="text-muted">إجمالي المنتجات</span>
            <span class="fw-bold">{{$subTotal}} ج.م</span>
        </div>
        <div class="summary-line" id="discountRow" style="display: none;">
            <span class="text-muted">خصم الكوبون</span>
            <span class="text-danger fw-bold" id="discountAmount">- 0 ج.م</span>
        </div>
        <div class="summary-line">
            <span class="text-muted">الشحن</span>
            <span class="text-success fw-bold">{{ auth()->user()->delivery->tax }} ج.م</span>
        </div>
        <div class="total-line d-flex justify-content-between align-items-center">
            <span>الإجمالي النهائي</span>
            <span class="text-accent" id="finalTotal">{{ $subTotal + auth()->user()->delivery->tax }} ج.م</span>
        </div>
    </div>
    <div class="glass-section shadow-sm">
        @foreach($carts as $item)
        <div class="order-item">
            <img src="{{ $item->product->image_url }}" class="product-img" alt="product">
            <div class="product-details">
                <div class="product-title" style="text-align:right">{{ $item->product->title }}</div>
                <div class="product-meta" style="text-align:right">سعر القطعة: {{ $item->product->price }} ج.م</div>
            </div>
            <div class="product-price-qty ">
                <span class="unit-price mb-1">{{ $item->product->price * $item->quantity }} ج.م</span>
                <span class="qty-label">الكمية: {{ $item->quantity }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <span class="section-label">طريقة الدفع</span>

        <div class="payment-card active shadow-sm" onclick="setPayment(this, 'cod')">
            <div class="payment-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="flex-grow-1">
                <div class="fw-bold">الدفع عند الاستلام</div>
                <div class="small text-muted">الدفع نقداً للمندوب فور وصول الطلب</div>
            </div>
            <input type="radio" name="type_payment" value="cashOnDelivery" checked class="d-none">
            <i class="fas fa-check-circle text-accent d-block"></i>
        </div>

        <div class="payment-card shadow-sm" onclick="setPayment(this, 'wallet')">
            <div class="payment-icon"><i class="fas fa-mobile-alt"></i></div>
            <div class="flex-grow-1">
                <div class="fw-bold">المحافظ الإلكترونية</div>
                <div class="small text-muted">فودافون كاش، إنستا باي</div>
            </div>
            <input type="radio" name="type_payment" value="wallet" class="d-none">
            <i class="far fa-circle text-muted"></i>
        </div>


    <div class="glass-section shadow-sm">
        <div class="summary-line">
            <span class="text-muted">إجمالي المنتجات</span>
            <span class="fw-bold">{{$subTotal}} ج.م</span>
        </div>
        <div class="summary-line">
            <span class="text-muted">الشحن</span>
            <span class="text-success fw-bold">{{ auth()->user()->delivery->tax }} ج.م</span>
        </div>
        <div class="total-line d-flex justify-content-between align-items-center">
            <span>الإجمالي النهائي</span>
            <span class="text-accent">{{ $subTotal + auth()->user()->delivery->tax }} ج.م</span>
        </div>
    </div>

    <button type="submit" form="finalOrderForm" class="btn-place-order">
        <i class="fas fa-shield-alt me-2"></i> إتمام الطلب بأمان
    </button>
    </form>


    <p class="text-center text-muted mt-3" style="font-size: 11px;">
        سيتم شحن طلبك إلى: <b>{{ auth()->user()->address }}</b>
    </p>
</div>
@endsection

@section('js')
<script>
    function setPayment(el, val) {
        $('.payment-card').removeClass('active').find('i.fa-check-circle').removeClass('fas fa-check-circle text-accent').addClass('far fa-circle text-muted');
        $(el).addClass('active').find('i.far').removeClass('far fa-circle text-muted').addClass('fas fa-check-circle text-accent');
        $(el).find('input').prop('checked', true);
    }
        $(document).ready(function() {
            $(document).on('click', '#openCart', function() {
                $('#sideCart, #cartOverlay').addClass('active');
                $('body').css('overflow', 'hidden');
            });

            $(document).on('click', '#closeCart, #cartOverlay', function() {
                $('#sideCart, #cartOverlay').removeClass('active');
                $('body').css('overflow', 'auto');
            });
            fetchCarts();

    });
    function updateQty(amt) {
        let input = $('#mainQty');
        let newVal = parseInt(input.val()) + amt;
        if (newVal >= 1) input.val(newVal);
    }

    $(document).on('click', '.add-to-cart-detail', function() {
        let btn = $(this);
        let id = btn.data('id');
        let qty = $('#mainQty').val();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...');

        $.ajax({
            url: '/cart',
            method: 'POST',
            data: { product_id: id, quantity: qty },
            success: function(res) {
                $('#cartToast').fadeIn().delay(2000).fadeOut();
                fetchCarts();
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-cart-plus me-2"></i> إضافة للسلة');
            }
        });
    });

        function fetchCarts() {
        $.ajax({
            url: '/carts',
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function(res) {
                carts = res.data;
                updateUI();
            },
            error: function() {
                console.error("خطأ في جلب بيانات السلة");
            }
        });
    }

    function updateUI() {
        const itemsCont = $('#cartItems');
        const cartTotal = $('#cartTotal');
        const cartBadge = $('#cartBadge');

        let total = 0;
        let count = 0;

        if (carts.length === 0) {
            itemsCont.html(`
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                    <p>السلة فارغة حالياً</p>
                </div>
            `);
        } else {
            itemsCont.empty();
            carts.forEach(cart => {
                total += cart.product.price * cart.quantity;
                count += cart.quantity;

                itemsCont.append(`
                    <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                        <img src="${cart.product.image_url}" width="60" height="60" class="rounded shadow-sm object-fit-cover">
                        <div class="ms-3 flex-grow-1 text-end">
                            <h6 class="mb-1 fw-bold small">${cart.product.title}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary small fw-bold">${cart.product.price} ج.م</span>
                                <div class="qty-controls d-flex align-items-center gap-2 bg-light rounded-pill px-2">
                                    <button class="btn btn-sm p-0 border-0" onclick="changeQty(${cart.id}, -1)">
                                        <i class="fas fa-minus-circle text-muted"></i>
                                    </button>
                                    <span class="fw-bold small">${cart.quantity}</span>
                                    <button class="btn btn-sm p-0 border-0" onclick="changeQty(${cart.id}, 1)">
                                        <i class="fas fa-plus-circle text-primary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button class="btn text-danger btn-sm ms-2" onclick="removeItem(${cart.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `);
            });
        }

        cartTotal.text(total + ' ج.م');
        cartBadge.text(count);
    }


    function changeQty(id, amt) {
        const item = carts.find(c => c.id === id);
        if (!item) return;

        if (item.quantity + amt <= 0) {
            removeItem(id);
            return;
        }

        $.ajax({
            url: `/cart/${id}` ,
            method: 'PUT',
            data: { quantity: amt },
            success: function() {
                fetchCarts();
            },
            error: function(xhr) {
                console.log(xhr);
                let errorMessage = "حدث خطأ ما";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showErrorToast(errorMessage);
            },
        });
    }

    function removeItem(id) {
        $.ajax({
            url: `/cart/${id}`,
            method: 'DELETE',
            success: function() {
                fetchCarts();
            },
        });
    }
    function showErrorToast(message) {
        let toast = $('#cartErrorToast');
        toast.text(message).fadeIn(200).delay(2500).fadeOut(200);
    }
</script>
@endsection


