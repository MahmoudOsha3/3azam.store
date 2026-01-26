@extends('layout.site.app')

@section('title', 'الاقسام | عظمه ستور')

@section('css')
<style>
    :root {
        --primary: #007bff; /* تأكد أن هذا هو لون متجرك الأساسي */
    }

    /* 1. تنسيق شبكة المنتجات */
    .product-grid {
        display: grid;
        /* في الكمبيوتر: يبدأ من اليمين ولا يتوسط إذا كان عنصراً واحداً */
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        justify-content: start;
    }

    @media (max-width: 991px) {
        .product-grid {
            grid-template-columns: 1fr;
            justify-items: center; /* يتوسط فقط في الموبايل لشكل أفضل */
        }
    }

    /* 2. الـ Sidebar الاحترافي (Desktop) */
    .filter-sidebar {
        background: #fff;
        border: 1px solid #f0f0f0 !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;
    }

    .list-group-item {
        border: none !important;
        padding: 12px 15px;
        margin-bottom: 8px;
        border-radius: 12px !important;
        background: #f8f9fa;
        color: #444;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-group-item:hover {
        background: #e9ecef !important;
        transform: translateX(-5px);
    }

    /* حالة القسم النشط في السايدبار */
    .list-group-item.active-category {
        background: var(--primary) !important;
        color: #fff !important;
    }

    /* 3. شريط الأقسام الأفقي (Mobile) */
    .mobile-categories {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        padding: 5px 0 15px;
        scrollbar-width: none;
        direction: rtl;
    }
    .mobile-categories::-webkit-scrollbar { display: none; }

    .category-pill {
        padding: 8px 20px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 50px;
        white-space: nowrap;
        font-size: 14px;
        cursor: pointer;
        transition: 0.3s;
    }

    .category-pill.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    /* 4. كارت المنتج */
    .product-card {
        background: #fff; border-radius: 15px; padding: 15px;
        transition: all 0.3s ease; height: 100%; border: 1px solid #f0f0f0 !important;
        width: 100%; max-width: 320px;
    }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .image-wrapper {
        position: relative; background: #f9f9f9; border-radius: 12px;
        overflow: hidden; height: 200px; display: flex; align-items: center; justify-content: center;
    }
    .image-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; }

    #paginationContainer .page-link {
        width: 40px; height: 40px; border-radius: 50% !important;
        margin: 0 3px; display: flex; align-items: center; justify-content: center;
        color: #555; border: 1px solid #eee;
    }
    #paginationContainer .page-item.active .page-link {
        background: var(--primary); border-color: var(--primary); color: #fff;
    }

#paginationContainer .pagination {
    gap: 8px;
    border: none;
}

#paginationContainer .page-item .page-link {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50% !important;
    border: 1px solid #eee;
    color: #555;
    font-weight: 600;
    transition: all 0.3s ease;
    background: #fff;
}

#paginationContainer .page-item.active .page-link {
    background-color: var(--primary);
    border-color: var(--primary);
    color: #fff;
    box-shadow: 0 4px 10px rgba(var(--bs-primary-rgb), 0.3);
}

#paginationContainer .page-item .page-link:hover:not(.active) {
    background-color: #f8f9fa;
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-3px);
}

#paginationContainer .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
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
<div class="container py-4 text-end" dir="rtl">
            <div class="toast-success" id="cartToast">تمت إضافة المنتج للسلة بنجاح!</div>
            <div class="toast-error" id="cartErrorToast"></div>

    <div class="mobile-categories-wrapper d-lg-none mb-4">
        <h6 class="fw-bold mb-2">تصفح الأقسام</h6>
        <div class="mobile-categories">
            @foreach($categories as $category)
                <div class="category-pill {{ $loop->first ? 'active' : '' }}"
                    data-id="{{ $category->id }}"
                    onclick="selectCategory({{ $category->id }}, this)">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="filter-sidebar p-4 rounded-4 sticky-top" style="top: 100px;">
                <h6 class="fw-bold mb-3">الأقسام</h6>
                <div class="list-group">
                    @foreach($categories as $category)
                    <div class="list-group-item {{ $loop->first ? 'active-category' : '' }}"
                        onclick="selectCategory({{ $category->id }}, this)">
                        <input type="radio" class="category-filter d-none"
                            name="category" value="{{ $category->id }}" {{ $loop->first ? 'checked' : '' }}>
                        <span>{{ $category->name }}</span>
                        <small class="opacity-75">({{ $category->products_count }})</small>
                    </div>
                    @endforeach
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3">السعر</h6>
                <input type="range" class="form-range" min="0" max="50000" id="priceRange" value="50000">
                <div class="d-flex justify-content-between small fw-bold mt-2">
                    <span id="priceValue" class="text-primary">50000</span>
                    <span>0</span>
                </div>
                <button class="btn btn-primary w-100 rounded-pill mt-3 fw-bold" onclick="fetchProducts()">تطبيق</button>
            </div>
        </div>


        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h4 class="fw-bold m-0">كل المنتجات</h4>
                <select class="form-select w-auto border-0 bg-light rounded-pill px-3" id="sortBy" onchange="fetchProducts()">
                    <option value="newest">الأحدث</option>
                    <option value="low_price">الأقل سعراً</option>
                    <option value="high_price">الأعلى سعراً</option>
                </select>
            </div>

            <div class="product-grid" id="productsGrid">
                </div>

            <div id="paginationContainer" class="mt-5"></div>
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
        fetchProducts();
        fetchCarts();

        $('#priceRange').on('input', function() {
            $('#priceValue').text($(this).val());
        });
    });

    function selectCategory(id, element) {
        $('.category-pill').removeClass('active');
        $(`.category-pill[data-id="${id}"]`).addClass('active');

        $('.list-group-item').removeClass('active-category');
        let radio = $(`.category-filter[value="${id}"]`);
        radio.prop('checked', true);
        radio.closest('.list-group-item').addClass('active-category');

        fetchProducts(1);
    }

    function fetchProducts(page = 1) {
        let categoryId = $('.category-filter:checked').val();
        let sort = $('#sortBy').val();
        let price = $('#priceRange').val();

        $('html, body').animate({
        scrollTop: $("#productsGrid").offset().top - 150
    }, 200);

        $('#productsGrid').html('<div class="text-center py-5 w-100"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i></div>');

        $.ajax({
            url: `categories/products/${categoryId}?page=${page}`,
            method: 'GET',
            data: { sort: sort, price : price },
            success: function(res) {
                renderProducts(res.data.data);
                renderPagination(res.data);
            }
        });
    }

    function renderProducts(products) {
        let grid = $('#productsGrid');
        grid.empty();

        if (!products || products.length === 0) {
            grid.html('<div class="col-12 text-center py-5 text-muted"><h5>لا توجد منتجات حالياً</h5></div>');
            return;
        }

        products.forEach(product => {
            let discountHtml = '';
            if (product.compare_price > product.price) {
                let discount = Math.round(((product.compare_price - product.price) / product.compare_price) * 100);
                discountHtml = `<span class="discount-badge" style="position:absolute; top:10px; right:10px; background:red; color:#fff; padding:2px 8px; border-radius:5px; font-size:12px;">-${discount}%</span>`;
            }

            grid.append(`
                <div class="product-card d-flex flex-column">
                    <div class="image-wrapper mb-3">
                        ${discountHtml}
                        <img src="${product.image_url}" alt="${product.title}">
                    </div>
                    <div class="product-info text-end flex-grow-1">
                        <small class="text-muted">${product.category.name}</small>
                        <h6 class="fw-bold my-2" style="font-size: 0.9rem;">${product.title}</h6>
                        <div class="mb-3">
                            <span class="fw-bold text-primary">${product.price} ج.م</span>
                            ${product.compare_price ? `<del class="text-muted small ms-2">${product.compare_price} ج.م</del>` : ''}
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="/product/${product.id}" class="btn btn-light btn-sm flex-fill rounded-3">التفاصيل</a>
                        <button class="btn btn-primary btn-sm flex-fill rounded-3 add-to-cart" data-id="${product.id}">
                            <i class="fas fa-cart-plus"></i> للسلة
                        </button>
                    </div>
                </div>
            `);
        });
    }

    function renderPagination(data) {
        let container = $('#paginationContainer');
        container.empty();

        // إذا كان هناك صفحة واحدة فقط، لا تعرض الترقيم
        if (data.last_page <= 1) return;

        let html = '<ul class="pagination pagination-md justify-content-center">';

        // زر السابق
        let prevDisabled = (data.current_page === 1) ? 'disabled' : '';
        html += `
            <li class="page-item ${prevDisabled}">
                <button class="page-link" onclick="fetchProducts(${data.current_page - 1})" ${prevDisabled}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </li>`;

        for (let i = 1; i <= data.last_page; i++) {
            if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                html += `
                    <li class="page-item ${i === data.current_page ? 'active' : ''}">
                        <button class="page-link shadow-none" onclick="fetchProducts(${i})">${i}</button>
                    </li>`;
            } else if (i === data.current_page - 2 || i === data.current_page + 2) {
                html += `<li class="page-item disabled"><span class="page-link border-0">...</span></li>`;
            }
        }

        let nextDisabled = (data.current_page === data.last_page) ? 'disabled' : '';
        html += `
            <li class="page-item ${nextDisabled}">
                <button class="page-link" onclick="fetchProducts(${data.current_page + 1})" ${nextDisabled}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            </li>`;

        html += '</ul>';
        container.html(html);
    }

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
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            headers: {
                'Accept': 'application/json'
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
