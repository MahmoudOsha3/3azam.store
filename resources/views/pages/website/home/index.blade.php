@extends('layout.site.app')

@section('title' , 'عظمه ستور')

@section('css')
<style>
    /* تنسيق الحاوية لتسمح بالتمرير الأفقي */
    .product-scroll-container {
        display: flex;
        overflow-x: auto;
        gap: 20px;
        padding: 20px 5px;
        scrollbar-width: thin; /* لمتصفحات Firefox */
        scrollbar-color: var(--primary) #eee;
        -webkit-overflow-scrolling: touch; /* تمرير سلس على iOS */
    }

    /* تحسين شكل شريط التمرير لمتصفحات Chrome/Edge/Safari */
    .product-scroll-container::-webkit-scrollbar {
        height: 6px;
    }
    .product-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .product-scroll-container::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    }

    /* تحديد عرض الكرت ليكون ثابتاً أثناء التمرير */
    .col-product {
        flex: 0 0 280px; /* العرض 280px ولا ينكمش */
        max-width: 280px;
    }

    @media (max-width: 576px) {
        .col-product {
            flex: 0 0 220px; /* في الموبايل يكون العرض أصغر قليلاً */
            max-width: 220px;
        }
    }

    .location-card {
        background: white;
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
        border: 1px solid #f1f5f9;
    }

    .location-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        background: var(--primary);
        color: white;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .map-badge {
        background: #eef2ff;
        color: var(--primary);
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 10px;
    }
    /* categories */
<style>
:root {
    --cat-bg: #fdfdfd;
    --cat-border: #f0f0f0;
    --cat-size: 110px;
}

.premium-categories {
    background: #ffffff;
}

.category-slider-container {
    overflow-x: auto;
    scrollbar-width: none; /* Firefox */
    -webkit-overflow-scrolling: touch;
    padding: 10px 0;
}

.category-slider-container::-webkit-scrollbar {
    display: none; /* Chrome/Safari */
}

.category-track {
    display: flex;
    gap: 20px;
    justify-content: flex-start; /* سيتغير للمنتصف بالـ JS لو العناصر قليلة */
}

/* الكارت الاحترافي */
.cat-card {
    flex: 0 0 auto;
    text-decoration: none;
    text-align: center;
    width: var(--cat-size);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.cat-image-wrapper {
    width: var(--cat-size);
    height: var(--cat-size);
    border-radius: 50%;
    position: relative;
    padding: 5px; /* مسافة بين الصورة والإطار */
    background: var(--cat-bg);
    border: 1px solid var(--cat-border);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 20px rgba(0,0,0,0.02);
}

.cat-image-wrapper img {
    width: 90%;
    height: 90%;
    object-fit: contain;
    border-radius: 50%;
    z-index: 2;
    transition: transform 0.5s ease;
}

/* التأثير السحري عند الهوفر */
.cat-card:hover .cat-image-wrapper {
    border-color: var(--primary);
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(79, 70, 229, 0.12);
    background: #fff;
}

.cat-card:hover img {
    transform: scale(1.1) rotate(5deg);
}

.cat-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(79, 70, 229, 0.05) 0%, transparent 70%);
    opacity: 0;
    transition: 0.3s;
}

.cat-card:hover .cat-overlay {
    opacity: 1;
}

/* نصوص الأقسام */
.cat-info {
    margin-top: 15px;
}

.cat-name {
    display: block;
    color: #2d3436;
    font-weight: 700;
    font-size: 0.95rem;
    transition: 0.3s;
}

.cat-count {
    font-size: 0.75rem;
    color: #a0a0a0;
    font-weight: 500;
}

.cat-card:hover .cat-name {
    color: var(--primary);
}

/* أزرار السكرول الاحترافية */
.scroll-btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 1px solid #eee;
    background: #fff;
    color: #555;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.3s;
}

.scroll-btn:hover {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

/* Responsive */
@media (max-width: 768px) {
    :root { --cat-size: 85px; }
    .category-track { gap: 15px; }
}
</style>

</style>
@endsection

@section('content')
    <header class="hero-wrapper">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6 hero-content">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 mb-3 fw-bold">وصل حديثاً لعام 2026</span>
                    <h1>تجربة تسوق <br><span style="color: var(--primary);">بلا حدود.</span></h1>
                    <p class="text-muted fs-5 my-4">اكتشف أرقى المنتجات العالمية بأفضل الأسعار مع خدمة توصيل سريعة وضمان استرجاع حقيقي.</p>
                    <div class="d-flex gap-3">
                        <button class="btn btn-primary btn-lg px-5 py-3 shadow">تسوق الآن</button>
                        <button class="btn btn-outline-dark btn-lg px-5 py-3">اكتشف المزيد</button>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://help.iubenda.com/wp-content/uploads/2023/03/ecommerce-website-builders.png" class="img-fluid rounded-4 shadow-lg" alt="Main Product">
                </div>
            </div>
        </div>
    </header>

<section class="premium-categories py-5">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-0">تسوق حسب القسم</h2>
                <p class="text-muted small mb-0">اكتشف أحدث التشكيلات العالمية</p>
            </div>
            <div class="d-none d-md-flex gap-2">
                <button class="scroll-btn prev"><i class="fas fa-chevron-right"></i></button>
                <button class="scroll-btn next"><i class="fas fa-chevron-left"></i></button>
            </div>
        </div>

        <div class="category-slider-container">
            <div class="category-track">
                @foreach($categories as $category)
                <a href="#" class="cat-card">
                    <div class="cat-image-wrapper">
                        <div class="cat-overlay"></div>
                        <img src="{{ $category->image_url ?? 'https://www.masrtimes.com/UploadCache/libfiles/51/8/600x338o/17.png' }}" alt="{{ $category->name }}">
                    </div>
                    <div class="cat-info">
                        <span class="cat-name">{{ $category->name }}</span>
                        <span class="cat-count">120 منتج</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

    <section class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0" style="font-size: 1.8rem;">أحدث المنتجات</h2>
            <a href="#" class="btn btn-outline-primary rounded-pill px-4">مشاهدة الكل</a>
        </div>

        <div class="product-scroll-container">
            @foreach($products as $product)
            <div class="col-product">
                <div class="product-card d-flex flex-column h-100">

                    <div class="image-wrapper mb-3">
                        @if($product->compare_price > $product->price)
                            @php
                                $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
                            @endphp
                            <span class="discount-badge">-{{ $discount }}%</span>
                        @endif
                        <img src="{{ $product->image_url }}" alt="{{ $product->title }}" loading="lazy">
                    </div>

                    <div class="product-info flex-grow-1">
                        <span class="category-name text-uppercase small text-muted">{{ $product->category->name ?? 'منوعات' }}</span>
                        <h5 class="product-title my-2 fw-bold text-dark">{{ $product->title }}</h5>

                        <div class="price-container mb-3">
                            <span class="current-price h5 fw-bold mb-0" style="color: var(--primary);">{{ number_format($product->price, 0) }} ج.م</span>
                            @if($product->compare_price)
                                <span class="old-price text-muted text-decoration-line-through ms-2" style="font-size: 0.9rem;">{{ number_format($product->compare_price, 0) }} ج.م</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-buttons d-flex gap-2">
                        <a href="#" class="btn-details flex-fill py-2 px-1">
                            <i class="fas fa-eye me-1"></i> التفاصيل
                        </a>
                        <button class="btn-add-cart add-to-cart flex-fill py-2 px-1"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->title }}"
                                data-price="{{ $product->price }}"
                                data-image="{{ $product->image_url }}">
                            <i class="fas fa-cart-plus me-1"></i> للسلة
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>


    <div class="toast-success" id="cartToast">تمت إضافة المنتج للسلة بنجاح!</div>
            <div class="toast-error" id="cartErrorToast"></div>

    <section class="bg-gray-light py-5 my-5" id="aboutUs">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="https://st2.depositphotos.com/1760420/5432/i/450/depositphotos_54324565-stock-photo-online-shopping-and-e-commerce.jpg" class="img-fluid rounded-4 shadow" alt="عن الموقع">
                        <div class="bg-white p-4 shadow-sm rounded-4 position-absolute bottom-0 start-0 m-3 d-none d-md-block" style="max-width: 200px;">
                            <h4 class="fw-bold text-primary m-0">+10 سنوات</h4>
                            <p class="small text-muted m-0">خبرة في السوق العربي</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h6 class="text-primary fw-bold text-uppercase ls-1 mb-3">من نحن</h6>
                    <h2 class="fw-bold mb-4">نحن لا نبيع المنتجات فقط، بل نقدم تجربة تسوق فريدة.</h2>
                    <p class="text-muted lh-lg mb-4">
                        بدأ متجرنا بفكرة بسيطة: توفير منتجات عالية الجودة بأسعار تنافسية. اليوم، نحن نفخر بتقديم آلاف المنتجات لعملائنا في جميع أنحاء الوطن العربي مع ضمان جودة حقيقي وخدمة ما بعد البيع لا تضاهى.
                    </p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success fs-4 me-2"></i>
                                <span class="fw-bold">ضمان أصالة المنتج</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success fs-4 me-2"></i>
                                <span class="fw-bold">توصيل خلال 48 ساعة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <section class="container py-5" id="locations">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-1">فروعنا</h6>
            <h2 class="fw-bold">أين تجدنا؟</h2>
            <p class="text-muted">نحن قريبون منك دائماً، تفضل بزيارتنا في أحد فروعنا الرسمية</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="location-card p-4 shadow-sm h-100">
                    <div class="map-badge"><i class="fas fa-map-marker-alt me-1"></i> الجيزة</div>
                    <div class="icon-circle shadow-sm">
                        <i class="fas fa-store"></i>
                    </div>
                    <h4 class="fw-bold mb-3">مزار مول - Mazar Mall</h4>
                    <p class="text-muted mb-4">الشيخ زايد، الحي الـ 16، اسكوير مول محور جمال عبدالناصر - الدور الأرضي.</p>

                    <div class="d-flex align-items-center mb-3 text-muted small">
                        <i class="far fa-clock me-2 text-primary"></i>
                        <span>يومياً: 10:00 صباحاً - 11:00 مساءً</span>
                    </div>

                    <a href="https://maps.app.goo.gl/Wj3etNhKyeAUTznKA" target="_blank" class="btn btn-outline-primary w-100 rounded-pill mt-2">
                        <i class="fas fa-directions me-1"></i> اتجاهات الموقع
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="location-card p-4 shadow-sm h-100">
                    <div class="map-badge"><i class="fas fa-map-marker-alt me-1"></i> القاهرة</div>
                    <div class="icon-circle shadow-sm" style="background: #2d3436;">
                        <i class="fas fa-building"></i>
                    </div>
                    <h4 class="fw-bold mb-3">المقر الرئيسي</h4>
                    <p class="text-muted mb-4">22 ش ترعة الاسماعيليه ابراج حواش برج الناصية شبرا الخيمة </p>

                    <div class="d-flex align-items-center mb-3 text-muted small">
                        <i class="far fa-clock me-2 text-primary"></i>
                        <span>الأحد - الخميس: 9:00 ص - 5:00 م</span>
                    </div>

                    <a href="tel:+201226110659" class="btn btn-outline-dark w-100 rounded-pill mt-2">
                        <i class="fas fa-phone-alt me-1"></i> اتصل بنا
                    </a>

                </div>
            </div>
        </div>
    </section>


    <section class="container py-5 mb-5">
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="p-4 rounded-4 bg-white border h-100">
                    <div class="icon-box mb-3 mx-auto bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-shipping-fast fs-3"></i>
                    </div>
                    <h5 class="fw-bold">توصيل سريع</h5>
                    <p class="text-muted small m-0">شحن مجاني للطلبات أكثر من 2000 ج.م</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4 rounded-4 bg-white border h-100">
                    <div class="icon-box mb-3 mx-auto bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-headset fs-3"></i>
                    </div>
                    <h5 class="fw-bold">دعم فني 24/7</h5>
                    <p class="text-muted small m-0">نحن هنا لمساعدتك في أي وقت خلال اليوم</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="p-4 rounded-4 bg-white border h-100">
                    <div class="icon-box mb-3 mx-auto bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-undo fs-3"></i>
                    </div>
                    <h5 class="fw-bold">استرجاع سهل</h5>
                    <p class="text-muted small m-0">سياسة استبدال واسترجاع خلال 14 يوم</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
<script>
    let carts = [];

    $(document).ready(function() {

        fetchCarts();

        $(document).on('click', '#openCart', function() {
            $('#sideCart, #cartOverlay').addClass('active');
            $('body').css('overflow', 'hidden');
        });

        $(document).on('click', '#closeCart, #cartOverlay', function() {
            $('#sideCart, #cartOverlay').removeClass('active');
            $('body').css('overflow', 'auto');
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
                showErrorToast('حدث خطأ في جلب بيانات سلة تسوقك');

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

    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        let button = $(this);
        let productId = button.data('id');

        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: 'cart',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                },
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
                button.prop('disabled', false).html('<i class="fas fa-cart-plus me-1"></i> للسلة');
            }
        });
    });

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
