@extends('layout.dashboard.app')

@section('title', 'مرحباً بك في عظمه ستور')

@section('css')
    <style>
        :root {
            --primary: #1a1a2e;
            --accent: #c5a059;
            --success: #10b981;
            --white: #ffffff;
            --bg-gray: #f8fafc;
            --sidebar-width: 350px;
        }

        .welcome-container {
                padding: 40px;
                min-height: 85vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--bg-gray);

                margin-right: var(--sidebar-width);
                transition: margin 0.3s ease;
            }

        .welcome-card {
            background: var(--white);
            width: 100%;
            max-width: 900px;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            overflow: hidden;
            display: flex;
            flex-direction: row-reverse;
            border: 1px solid #eef2f6;
        }

        .welcome-visual {
            flex: 1;
            background: var(--primary);
            color: var(--accent);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .welcome-visual i { font-size: 5rem; margin-bottom: 20px; }
        .welcome-visual h2 { font-weight: 900; letter-spacing: 2px; margin: 0; }

        .welcome-content {
            flex: 1.5;
            padding: 60px 40px;
            text-align: right;
        }

        .welcome-content h1 {
            font-size: 2.2rem;
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .welcome-content p {
            color: #64748b;
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .quick-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 35px;
        }

        .mini-info-card {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 12px;
            border-right: 4px solid var(--accent);
        }

        .mini-info-card span { display: block; font-size: 11px; color: #94a3b8; margin-bottom: 5px; }
        .mini-info-card b { font-size: 14px; color: var(--primary); }

        .btn-enter {
            background: var(--primary);
            color: white;
            padding: 15px 40px;
            border-radius: 12px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-enter:hover {
            background: var(--accent);
            color: var(--primary);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .welcome-card { flex-direction: column; text-align: center; }
            .welcome-content { padding: 40px 20px; text-align: center; }
            .quick-info-grid { grid-template-columns: 1fr; }
            .btn-enter { width: 100%; justify-content: center; }
        }
        @media (max-width: 992px) {
            .welcome-container {
                margin-right: 0;
                padding: 20px;
            }
            .welcome-card {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
<main class="welcome-container">
    <div class="welcome-card">
        <div class="welcome-visual">
            <i class="fas fa-crown"></i>
            <h2>عظمة ستور</h2>
            <p style="color: rgba(255,255,255,0.6); margin-top: 10px;">EST. {{ now()->year }}</p>
        </div>

        <div class="welcome-content">
            <div id="liveClock" style="color: var(--accent); font-weight: bold; margin-bottom: 10px;"></div>
            <h1>أهلاً بك في عظمه ستور 👋</h1>
            <p>مرحباً بك يا <b>{{ auth()->user()->name }}</b>. أنت الآن في قلب لوحة تحكمك، حيث يمكنك التحكم علي موقع عظمة ستور.</p>

            <div class="quick-info-grid">
                <div class="mini-info-card">
                    <span>حالة النظام</span>
                    <b><i class="fas fa-check-circle" style="color:var(--success)"></i> متصل ومستقر</b>
                </div>
                <div class="mini-info-card">
                    <span>توقيت الدخول</span>
                    <b>{{ session('login_time', now()->format('h:i A')) }}</b>
                </div>
                <div class="mini-info-card">
                    <span>صلاحية الوصول</span>
                    <b>{{ auth()->user()->role->name ?? 'موظف متميز' }}</b>
                </div>
                <div class="mini-info-card">
                    <span>تاريخ اليوم</span>
                    <b>{{ date('Y/m/d') }}</b>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script>
    function updateClock() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        document.getElementById('liveClock').innerText = now.toLocaleTimeString('ar-EG', options);
    }

    $(document).ready(function() {
        setInterval(updateClock, 1000);
        updateClock();

        $('.welcome-card').hide().fadeIn(1000);
    });
</script>
@endsection
