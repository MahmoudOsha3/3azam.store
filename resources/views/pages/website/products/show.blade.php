@extends('layout.site.app')

@section('title', $product->title . ' | عظمه ستور')

@section('css')
<style>
    :root { --primary: #007bff; --secondary: #6c757d; }

    .product-container { background: #fff; border-radius: 20px; overflow: hidden; }

    /* معرض الصور */
    .main-image-wrapper {
        background: #f9f9f9;
        border-radius: 15px;
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }
    .main-image-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; transition: 0.3s; }

    /* معلومات المنتج */
    .product-info-box { padding: 20px; }
    .badge-category { background: #e7f1ff; color: var(--primary); font-weight: 600; padding: 5px 15px; border-radius: 50px; font-size: 13px; }
    .product-price { font-size: 2rem; font-weight: 800; color: var(--primary); }
    .old-price { text-decoration: line-through; color: #adb5bd; font-size: 1.2rem; margin-right: 10px; }

    /* التحكم في الكمية */
    .qty-input-group {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 50px;
        width: fit-content;
        padding: 5px;
    }
    .qty-btn {
        width: 40px; height: 40px; border-radius: 50%; border: none;
        background: #fff; display: flex; align-items: center; justify-content: center;
        transition: 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .qty-btn:hover { background: var(--primary); color: #fff; }
    .qty-val { width: 50px; text-align: center; font-weight: bold; border: none; background: transparent; }

    /* أزرار الإجراءات */
    .btn-add-main {
        background: var(--primary); color: #fff; border: none;
        padding: 15px 40px; border-radius: 12px; font-weight: bold;
        transition: 0.3s; flex-grow: 1;
    }
    .btn-add-main:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,123,255,0.2); color: #fff; }

    /* التبويبات والمواصفات */
    .nav-tabs { border: none; gap: 10px; }
    .nav-link {
        border: none !important; color: #555; font-weight: 600;
        padding: 10px 25px; border-radius: 10px !important;
    }
    .nav-link.active { background: var(--primary) !important; color: #fff !important; }
    .specs-table tr td { padding: 15px; border-color: #f0f0f0; }

    @media (max-width: 991px) {
        .main-image-wrapper { height: 300px; }
        .product-price { font-size: 1.5rem; }
        .product-container { border-radius: 0; }
    }
    .toast-error {
    position: fixed;
    top: 30px;
    left: 30px;
    background: #dc3545;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 14px;
    display: none;
    z-index: 9999;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
</style>
@endsection

@section('content')
<div class="container py-lg-5 py-3 text-end" dir="rtl">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="/categories">الأقسام</a></li>
            <li class="breadcrumb-item active">{{ $product->title }}</li>
        </ol>
    </nav>


    <div class="row g-4 product-container">
        <div class="col-lg-6">
            <div class="main-image-wrapper shadow-sm">
                <img src="{{ $product->image_url }}" id="mainProductImg" alt="{{ $product->title }}">
            </div>
    <div class="toast-success" id="cartToast">تمت إضافة المنتج للسلة بنجاح!</div>
            <div class="toast-error" id="cartErrorToast"></div>


            @if($product->images)
            <div class="d-flex gap-2 mt-3">
                @foreach($product->images as $img)
                <div class="img-thumb" style="width: 80px; height: 80px; cursor: pointer; border: 1px solid #eee; border-radius: 10px; overflow: hidden;" onclick="document.getElementById('mainProductImg').src = this.querySelector('img').src">
                    <img src="{{ $img->url }}" class="w-100 h-100 object-fit-cover">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-lg-6">
            <div class="product-info-box">
                <span class="badge-category" >{{ $product->category->name }}</span>
                <h1 class="fw-bold mt-3 mb-2 h2" style="display: flex;direction: rtl;">{{ $product->title }}</h1>

                <div class="d-flex align-items-center mb-4">
                    <span class="product-price">{{ $product->price }} ج.م</span>
                    @if($product->compare_price)
                    <span class="old-price">{{ $product->compare_price }} ج.م</span>
                    <span class="badge bg-danger rounded-pill">وفر {{ round((($product->compare_price - $product->price)/$product->compare_price)*100) }}%</span>
                    @endif
                </div>

                <p class="text-muted mb-4 lh-lg" style="display: flex;direction: rtl;">
                    {{ Str::limit($product->description, 300) }}
                </p>

                <hr class="my-4 opacity-50">

                <div class="d-flex flex-column gap-4">
                    <div class="qty-selection">
                        <label class="fw-bold mb-2 d-block" style="text-align:right">الكمية:</label>
                        <div class="qty-input-group" >
                            <button class="qty-btn" onclick="updateQty(1)"><i class="fas fa-plus"></i></button>
                            <input type="number" id="mainQty" class="qty-val" value="1" readonly>
                            <button class="qty-btn" onclick="updateQty(-1)"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="d-flex gap-3 align-items-center">
                        <button class="btn-add-main add-to-cart-detail" data-id="{{ $product->id }}">
                            <i class="fas fa-cart-plus me-2"></i> إضافة للسلة
                        </button>
                    </div>
                </div>

                <div class="row mt-5 g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 p-3 bg-light rounded-4">
                            <i class="fas fa-truck text-primary fs-4"></i>
                            <small class="fw-bold">توصيل سريع لكل المحافظات</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 p-3 bg-light rounded-4">
                            <i class="fas fa-undo text-primary fs-4"></i>
                            <small class="fw-bold">سياسة استرجاع 14 يوم</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs mb-4" id="productTabs">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc-tab">الوصف</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#specs-tab">المواصفات</button>
                </li>
            </ul>
            <div class="tab-content p-4 bg-white rounded-4 shadow-sm border">
                <div class="tab-pane fade show active" id="desc-tab">
                    <div class="lh-lg">{{ $product->description }}</div>
                </div>
                <div class="tab-pane fade" id="specs-tab">
                    <table class="table specs-table m-0">
                        <tr>
                            <td class="bg-light fw-bold w-25">الماركة</td>
                            <td>{{ $product->brand ?? 'عظمه ستور' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold">حالة المنتج</td>
                            <td>جديد (أصلي)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
            error: function(xhr) {
                console.log(xhr);
                let errorMessage = "حدث خطأ ما";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showErrorToast(errorMessage);
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
