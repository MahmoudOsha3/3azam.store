@extends('layout.site.app')

@section('title', 'إنشاء حساب | عظمه ستور')

@section('css')
<style>
    .auth-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa; /* خلفية هادئة */
    }
    .auth-card {
        width: 100%;
        max-width: 450px;
        background: #fff;
        border-radius: 25px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }
    .auth-header h2 { font-weight: 800; color: #333; }
    .auth-header p { color: #777; font-size: 14px; }

    .form-label { font-weight: 600; font-size: 14px; margin-bottom: 8px; display: block; text-align: right; }

    .input-group-custom {
        position: relative;
        margin-bottom: 20px;
    }
    .input-group-custom i {
        position: absolute;
        top: 70%;
        right: 15px;
        transform: translateY(-50%);
        color: var(--primary);
    }
    .form-control-custom {
        width: 100%;
        padding: 12px 45px 12px 15px;
        border-radius: 12px;
        border: 1px solid #eee;
        background: #fdfdfd;
        transition: 0.3s;
        text-align: right;
    }
    .form-control-custom:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(0,123,255,0.1);
        outline: none;
    }

    .btn-auth {
        background: var(--primary);
        color: #fff;
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
        margin-top: 10px;
    }
    .btn-auth:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,123,255,0.3); }

    .social-login {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }
    .social-btn {
        flex: 1;
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 12px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: 0.3s;
    }
    .social-btn:hover { background: #f8f9fa; }
</style>
@endsection
@section('content')
<div class="auth-wrapper py-5">
    <div class="auth-card">
        <div class="auth-header text-center mb-4">
            <h2>انضم إلينا</h2>
            <p>استمتع بتجربة تسوق فريدة وعروض حصرية</p>
        </div>

        <form action="{{route('user.store.site')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group-custom">
                        <label class="form-label">الاسم الكامل</label>
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="form-control-custom" placeholder="أدخل اسمك الثلاثي" required>
                        @error('name')
                            <span>{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="input-group-custom">
                        <label class="form-label">البريد الإلكتروني</label>
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control-custom" placeholder="mail@example.com" required>
                        @error('email')
                            <span>{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="input-group-custom">
                        <label class="form-label">العنوان </label>
                        <i class="fas fa-user"></i>
                        <input type="text" name="address" class="form-control-custom" placeholder="أدخل العنوان بالتفصيل يستخدم للتوصيل" required>
                        @error('address')
                            <span>{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="form-label">رقم الهاتف</label>
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" class="form-control-custom" placeholder="01xxxxxxxxx" required>
                        @error('phone')
                            <span>{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="form-label">المحافظة</label>
                        <select name="delivery_id" class="form-control-custom">
                            <option seleted disabled>اختار المحافظة .... </option>
                            @foreach($deliveries as $delivery)
                                <option value="{{$delivery->id}}">{{ $delivery->government }}</option>
                            @endforeach
                        </select>
                        @error('delivery_id')
                            <span>{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="form-label">كلمة المرور</label>
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control-custom" placeholder="••••••••" required>
                        @error('password')
                            <span>{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="form-label">تأكيد كلمة المرور</label>
                        <i class="fas fa-check-double"></i>
                        <input type="password" name="password_confirmation" class="form-control-custom" placeholder="••••••••" required>
                        @error('password_confirmation')
                            <span>{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- <div class="form-check text-end mb-3" dir="rtl">
                <input class="form-check-input float-end ms-2" type="checkbox" id="terms" required>
                <label class="form-check-label small text-muted" for="terms">
                    أوافق على <a href="#" class="text-primary">الشروط والأحكام</a> وسياسة الخصوصية
                </label>
            </div> --}}

            <button type="submit" class="btn-auth">إنشاء الحساب</button>
        </form>

        <div class="text-center mt-4 border-top pt-3">
            <span class="text-muted small">لديك حساب بالفعل؟</span>
            <a href="{{route('user.login.view')}}" class="text-primary fw-bold text-decoration-none small">سجل دخول من هنا</a>
        </div>
    </div>
</div>
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
            $('body').css('overflow', 'auto'); // إعادة التمرير
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
                alert(xhr.responseJSON?.message);
            }
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
    </script>
@endsection
