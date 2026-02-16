@extends('layout.site.app')

@section('title', 'تسجيل الدخول | عظمه ستور')

@section('css')
<style>
    :root {
        --primary: #007bff; /* تأكد من تعريف اللون الأساسي إذا لم يكن معرفاً */
    }
    .auth-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa; /* خلفية هادئة */
        padding: 20px 0;
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

    /* تنسيق أزرار السوشيال ميديا الجديد */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 25px 0;
        color: #ccc;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
    }
    .divider:not(:empty)::before { margin-left: .5em; }
    .divider:not(:empty)::after { margin-right: .5em; }

    .social-login {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    .social-btn {
        flex: 1;
        padding: 12px;
        border: 1px solid #eee;
        border-radius: 12px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
        color: #333;
    }
    .social-btn:hover { 
        background: #f8f9fa; 
        border-color: #ddd;
        transform: translateY(-2px);
    }
    .social-btn i { font-size: 18px; }
    .btn-google i { color: #DB4437; }
    .btn-facebook i { color: #4267B2; }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header text-center mb-4">
            <h2>أهلاً بك مجدداً!</h2>
            <p>قم بتسجيل الدخول لمتابعة تسوقك في عظمه ستور</p>
        </div>

        <form action="{{ route('user.login.check') }}" method="POST">
            @csrf
            <div class="input-group-custom">
                <label class="form-label">البريد الإلكتروني</label>
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-control-custom" placeholder="example@mail.com" value="{{ old('email') }}" required>
                @error('email')
                    <span style="color:brown; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group-custom">
                <div class="d-flex justify-content-between">
                    <label class="form-label">كلمة المرور</label>
                </div>
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control-custom" placeholder="••••••••" required>
                @error('password')
                    <span style="color:brown; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-auth">دخول</button>
        </form>

        <div class="divider small">أو سجل دخول بواسطة</div>

        <div class="social-login">
            <a href="{{ route('socialite.login' , 'google') }}" class="social-btn btn-google">
                <i class="fab fa-google"></i>
                Google
            </a>
            <a href="{{ route('socialite.login' , 'facebook') }}" class="social-btn btn-facebook">
                <i class="fab fa-facebook-f"></i>
                Facebook
            </a>
        </div>

        <div class="text-center mt-4">
            <span class="text-muted small">ليس لديك حساب؟</span>
            <a href="{{route('user.create.view')}}" class="text-primary fw-bold text-decoration-none small">إنشاء حساب جديد</a>
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
            data: { 
                quantity: amt,
                _token: '{{ csrf_token() }}' // إضافة التوكن للعمليات التي تحتاج ذلك
            },
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
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                fetchCarts();
            },
        });
    }
</script>
@endsection