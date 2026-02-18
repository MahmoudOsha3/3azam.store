@php
    $isActive = function ($patterns = []) {
        foreach ($patterns as $pattern) {
            if (request()->is($pattern)) return true;
        }
        return false;
    };
@endphp

<button class="mobile-toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars" id="toggleIcon"></i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<aside class="sidebar" id="mainSidebar">
    <div class="profile-card" style="padding: 20px 15px; text-align: center; background: rgba(0,0,0,0.05); margin-bottom: 10px;">
        <div style="position: relative; display: inline-block; margin-bottom: 10px;">
            @php
                $avatarUrl = auth()->user()->image
                    ? asset('storage/' . auth()->user()->image)
                    : "https://ui-avatars.com/api/?name=" . urlencode(auth()->user()->name ?? "") . "&background=e67e22&color=fff&bold=true";
            @endphp
            <img src="{{ $avatarUrl }}" alt="Avatar">
            <span style="position: absolute; bottom: -2px; left: -2px; width: 12px; height: 12px; background: #2ecc71; border: 2px solid var(--secondary); border-radius: 50%;"></span>
        </div>

        <div class="profile-info">
            <h5 style="margin: 0; color: #fff; font-size: 0.95rem; font-weight: 600;">{{ auth()->user()->name ?? "Mahmoud Abdelrahim" }}</h5>
            <div style="margin-top: 5px;">
                <span style="color: var(--primary); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; background: rgba(230, 126, 34, 0.1); padding: 2px 8px; border-radius: 4px;">
                    {{ auth()->user()->role->name ?? 'Admin' }}
                </span>
            </div>
        </div>
    </div>

    <div class="menu-container" style="padding: 10px; flex-grow: 1; overflow-y: auto;">
        @foreach (\App\Services\Sidebar\SidebarService::items() as $key => $properties)

        {{-- @foreach (config('sidebar') as $key => $properties) --}}
            <a href="{{ url($properties['route']) }}" class="menu-item"
               style="{{ $isActive($properties['active']) ? 'background: var(--primary); color: white;' : 'color: #a4b0be;' }}"
               onmouseover="if(!{{ $isActive($properties['active']) ? '1' : '0' }}) this.style.background='rgba(255,255,255,0.05)';"
               onmouseout="if(!{{ $isActive($properties['active']) ? '1' : '0' }}) this.style.background='transparent';">

                <i class="{{ $properties['icon'] }}" style="width: 20px; font-size: 1rem; margin-left: 10px;"></i>
                <span style="font-size: 0.9rem;">{{ $properties['label'] }}</span>
            </a>
        @endforeach
            <div style="padding: 15px; border-top: 1px solid rgba(255,255,255,0.05);">
        <form action="{{route('logout')}}" method="POST">
            @csrf
            <button type="submit" style="width: 100%; background: rgba(255, 71, 87, 0.1); border: none; color: #ff4757; padding: 10px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem; font-weight: bold; transition: 0.3s;"
                onmouseover="this.style.background='rgba(255, 71, 87, 0.2)'"
                onmouseout="this.style.background='rgba(255, 71, 87, 0.1)'">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
    </div>


</aside>
