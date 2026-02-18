<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="{{ asset('js/dashboard/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @yield('css')
    <style>
    :root {
        --sidebar-width: 260px;
        --sidebar-bg: #1a1a2e;
        --accent-color: #e67e22;
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        right: 0;
        background: var(--sidebar-bg);
        display: flex;
        flex-direction: column;
        z-index: 1000;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
    }

    /* منطقة البروفايل ثابتة في الأعلى */
    .profile-card {
        flex-shrink: 0; /* يمنع انضغاط قسم البروفايل */
        background: rgba(0,0,0,0.2);
        padding: 30px 15px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .menu-container {
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px 10px;
    }

    /* تخصيص شكل السكرول بار ليناسب التصميم */
    .menu-container::-webkit-scrollbar {
        width: 5px;
    }
    .menu-container::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
    }

    /* منطقة الخروج ثابتة في الأسفل */
    .logout-wrapper {
        flex-shrink: 0;
        padding: 15px;
        background: rgba(0,0,0,0.2);
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        margin-bottom: 5px;
        border-radius: 10px;
        transition: 0.3s;
        text-decoration: none;
    }
</style>
</head>
<body>

<div id="app dashboard-wrapper" style="display: flex; direction: rtl;">

    @include('layout.dashboard.sidebar')

    @yield('content')


</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-left",
            timeOut: 3000,
            extendedTimeOut: 1000,
            newestOnTop: true,
            preventDuplicates: true,
            rtl: true,
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>




@yield('js')

</body>
</html>
