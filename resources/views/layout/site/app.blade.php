<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site/home.css') }}">
    @yield('css')
    <style>
        .top-marquee {
            overflow: hidden;
            position: relative;
            font-weight: 700;
        }

        .top-marquee .marquee {
            display: flex;
            gap: 50px;
            white-space: nowrap;
            animation: marqueeAnim 10s linear infinite;
        }

        .top-marquee .marquee span {
            display: inline-block;
        }

        /* Animation */
        @keyframes marqueeAnim {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .top-marquee .marquee {
                animation: marqueeAnim 25s linear infinite;
                gap: 30px;
            }
            .top-marquee {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .top-marquee {
                font-size: 0.75rem;
            }
        }

    </style>
</head>
<body>
    <section class="top-marquee bg-primary text-white py-2">
        <div class="marquee">
            <span>🔥 أفضل العروض الآن بمناسبة رمضان! | 🚚 توصيل سريع لجميع المحافظات | ⭐ خصومات خاصة علي البوكسات </span>
            <span>🔥 أفضل العروض الآن بمناسبة رمضان! | 🚚 توصيل سريع لجميع المحافظات | ⭐ خصومات خاصة علي البوكسات </span>
        </div>
    </section>

    @include('layout.site.header')

    @yield('content')

    @include('layout.site.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @yield('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'Accept' : 'application/json' ,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

</body>
</html>
