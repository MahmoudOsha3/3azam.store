<div class="side-cart" id="sideCart">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="m-0 fw-bold">سلة التسوق</h5>
        <button class="btn-close" id="closeCart"></button>
    </div>

    <div class="p-4 flex-grow-1 overflow-auto" id="cartItems">
        <div class="text-center text-muted py-5">
            <i class="fas fa-shopping-basket fa-3x mb-3"></i>
            <p>السلة فارغة حالياً</p>
        </div>
    </div>

    <div class="p-4 border-top cart-footer">
        <div class="d-flex justify-content-between mb-3 fw-bold fs-5">
            <span>الإجمالي:</span>
            <span id="cartTotal" class="text-primary">0 ج.م</span>
        </div>
        <a style="margin-bottom: 90px" href="{{route('carts.index')}}" class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-3 text-white text-decoration-none d-block text-center">
            إتمام عملية الشراء
        </a>
    </div>
</div>

<div class="filter-overlay" id="cartOverlay"></div>

<nav class="navbar navbar-expand-lg main-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}" style="color: var(--primary);">3azma Store</a>

        <div class="d-flex align-items-center gap-2 gap-md-3 order-lg-last">

            <div class="auth-buttons d-flex align-items-center gap-2">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle fw-bold btn-sm-mobile" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">حسابي</a></li>
                            <li>
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </li>
                            {{-- <li><a class="dropdown-item text-danger" href="#">خروج</a></li> --}}
                        </ul>
                    </div>
                @else
                    <a href="{{route('user.login.view')}}" class="btn btn-light fw-bold btn-sm-mobile">تسجيل الدخول</a>
                    <a href="{{route('user.create.view')}}" class="btn btn-primary fw-bold d-none d-sm-block">سجل الآن</a>
                @endauth
            </div>

            <div class="position-relative ms-1 ms-md-2" style="cursor: pointer;" id="openCart">
                <i class="fas fa-shopping-cart" style="font-size: 20px"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartBadge" style="font-size: 0.7rem;">0</span>
            </div>

            <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <i class="fas fa-bars fs-3"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center text-lg-start">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">الأقسام</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{route('orders.me')}}">طلباتي</a></li>
                @endauth
                <li class="nav-item"><a class="nav-link" href="#aboutUs">من نحن</a></li>

            </ul>
        </div>
    </div>
</nav>
