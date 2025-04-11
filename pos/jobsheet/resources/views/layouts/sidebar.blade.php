@php
    // Set default value for $activeMenu if not defined
    $activeMenu = $activeMenu ?? '';
@endphp
<div class="sidebar">
    <!-- Sidebar Brand -->
    <div class="brand-container">
        <a href="{{ url('/') }}" class="brand-link">
            <i class="fas fa-shopping-cart brand-image"></i>
            <span class="brand-text">BluePos</span>
        </a>
    </div>
    
    <!-- User Panel -->
    <div class="user-panel">
        <div class="user-avatar">
            <img src="{{ auth()->user()->profile_picture ? asset(auth()->user()->profile_picture) : asset('images/default-avatar.png') }}" 
                alt="User Image">
        </div>
        <div class="user-info">
            <a href="{{ url('/profile') }}">
                <h6>{{ auth()->user()->name }}</h6>
                <span class="role-badge {{ strtolower(auth()->user()->getRoleName()) }}">
                    {{ auth()->user()->getRoleName() }}
                </span>
            </a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <!-- Dashboard -->
            <li class="nav-item {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- User Management -->
            <li class="nav-section">
                <span class="nav-section-title">MANAJEMEN PENGGUNA</span>
            </li>
            <li class="nav-item {{ in_array($activeMenu, ['users', 'roles']) ? 'active' : '' }}">
                <a href="{{ url('/users') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <span>Daftar Pengguna</span>
                </a>
            </li>

            <!-- Item Management -->
            <li class="nav-section">
                <span class="nav-section-title">BARANG</span>
            </li>
            <li class="nav-item {{ in_array($activeMenu, ['item-types', 'items', 'items-import']) ? 'active' : '' }}">
                <a href="{{ url('/items') }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <span>Daftar Barang</span>
                </a>
            </li>

            <!-- Order Management -->
            <li class="nav-section">
                <span class="nav-section-title">PESANAN</span>
            </li>
            <li class="nav-item {{ in_array($activeMenu, ['orders', 'create-order', 'orders-export']) ? 'active' : '' }}">
                <a href="#" class="has-dropdown">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <span>Pesanan</span>
                    <i class="dropdown-icon fas fa-angle-down"></i>
                </a>
                <ul class="submenu">
                    <li class="submenu-item {{ ($activeMenu == 'create-order') ? 'active' : '' }}">
                        <a href="{{ url('/orders/create') }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <span>Buat Pesanan</span>
                        </a>
                    </li>
                    <li class="submenu-item {{ ($activeMenu == 'orders') ? 'active' : '' }}">
                        <a href="{{ url('/orders') }}">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <span>Daftar Transaksi</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- User Profile -->
            <li class="nav-section">
                <span class="nav-section-title">AKUN</span>
            </li>
            <li class="nav-item {{ ($activeMenu == 'profile') ? 'active' : '' }}">
                <a href="{{ url('/profile') }}">
                    <i class="nav-icon fas fa-user-circle"></i>
                    <span>Profil</span>
                </a>
            </li>
            <li class="nav-item logout">
                <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    
    <!-- Water Effect -->
    <div class="sidebar-water-effect">
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
        <div class="bubble bubble-4"></div>
        <div class="bubble bubble-5"></div>
        <div class="bubble bubble-6"></div>
    </div>
</div>