@extends('layout.site.app')

@section('title', 'طلباتي | عظمه ستور')

@section('css')
<style>
    :root { --border-gray: #d5d9d9; --bg-gray: #f0f2f2; --text-main: #1d1d1f; --text-sec: #565959; }
    body { background-color: #f8f9fa; font-family: 'Cairo', sans-serif; }

    .orders-wrapper { max-width: 900px; margin: 40px auto; padding: 0 15px; }

    /* الحاوية الرئيسية للطلب */
    .order-card {
        background: #fff; border: 1px solid var(--border-gray);
        border-radius: 8px; margin-bottom: 30px; overflow: hidden;
    }

    /* رأس الطلب (Amazon Style Header) */
    .order-card-header {
        background-color: var(--bg-gray); padding: 15px 20px;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--border-gray);
    }

    .header-meta { display: flex; gap: 40px; }
    .meta-item { display: flex; flex-direction: column; }
    .meta-label { font-size: 12px; color: var(--text-sec); margin-bottom: 2px; }
    .meta-value { font-size: 14px; font-weight: 700; color: var(--text-main); }

    /* جسم الطلب */
    .order-card-body { padding: 20px; }

    .order-status-heading {
        font-size: 18px; font-weight: 800; margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px;
    }

    .product-content { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; }

    .product-info-flex { display: flex; gap: 20px; flex-grow: 1; }
    .product-img { width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #eee; }

    .product-text h6 { font-weight: 700; font-size: 15px; margin-bottom: 5px; color: #007185; }
    .product-text p { font-size: 13px; color: var(--text-sec); margin: 0; }

    /* أزرار التحكم الجانبية */
    .order-actions-sidebar { display: flex; flex-direction: column; gap: 10px; width: 220px; }
    .btn-custom {
        display: block; width: 100%; padding: 8px 15px; border-radius: 8px;
        font-size: 13px; font-weight: 600; text-align: center; text-decoration: none;
        transition: 0.2s; border: 1px solid var(--border-gray); background: #fff; color: var(--text-main);
    }
    .btn-custom:hover { background: #f7fafa; border-color: #adb1b1; }
    .btn-primary-style { background: #FFD814; border-color: #FCD200; } /* لون أزرار أمازون الشهير */
    .btn-primary-style:hover { background: #F7CA00; }

    /* التجاوب مع الشاشات */
    @media (max-width: 768px) {
        .order-card-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .header-meta { gap: 20px; width: 100%; justify-content: space-between; }
        .product-content { flex-direction: column; }
        .order-actions-sidebar { width: 100%; }
        .product-info-flex { width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="orders-wrapper" dir="rtl">
    <h2 class="fw-bold mb-4" style="text-align: right;">طلباتك</h2>

    @foreach($orders as $order)
    <div class="order-card">
        <div class="order-card-header">
            <div class="header-meta">
                <div class="meta-item">
                    <span class="meta-label">تاريخ الطلب</span>
                    <span class="meta-value">{{ $order->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">الإجمالي</span>
                    <span class="meta-value">{{ $order->total_price }} ج.م</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">إرسال إلى</span>
                    <span class="meta-value text-info">{{ auth()->user()->name }}</span>
                </div>
            </div>
            <div class="meta-item text-md-start">
                <span class="meta-label">رقم الطلب #{{ $order->number_order }}</span>
            </div>
            <div class="order-actions-sidebar">
                <a href="{{route('order.show' , $order->id)}}" class="btn-custom">عرض تفاصيل الطلب</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('js')
<script>
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
