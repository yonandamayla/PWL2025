@php
    // Set default value for $activeMenu if not defined
    $activeMenu = $activeMenu ?? '';
    $isAdmin = auth()->user()->role_id === 1;
    $isCashier = auth()->user()->role_id === 2;
    $isCustomer = auth()->user()->role_id === 3; // Assuming role_id 3 for customers
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

            @if($isAdmin)
            <!-- User Management - Admin Only -->
            <li class="nav-section">
                <span class="nav-section-title">MANAJEMEN PENGGUNA</span>
            </li>
            <li class="nav-item {{ in_array($activeMenu, ['users', 'roles']) ? 'active' : '' }}">
                <a href="{{ url('/users') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <span>Daftar Pengguna</span>
                </a>
            </li>
            
            <!-- Item Management - Admin Only -->
            <li class="nav-section">
                <span class="nav-section-title">BARANG</span>
            </li>
            <li class="nav-item {{ in_array($activeMenu, ['item-types', 'items', 'items-import']) ? 'active' : '' }}">
                <a href="{{ url('/items') }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <span>Daftar Barang</span>
                </a>
            </li>
            @endif

            <!-- Transaction Management Section - Different for each role -->
            <li class="nav-section">
                <span class="nav-section-title">{{ ($isAdmin || $isCashier) ? 'TRANSAKSI' : 'PESANAN' }}</span>
            </li>

            @if($isAdmin || $isCashier)
            <!-- Admin and Cashier Transaction Menu -->
            <li class="nav-item {{ in_array($activeMenu, ['orders']) ? 'active' : '' }}">
                <a href="{{ url('/orders') }}">
                    <i class="nav-icon fas fa-list-alt"></i>
                    <span>Daftar Transaksi</span>
                </a>
            </li>
            
            <li class="nav-item {{ in_array($activeMenu, ['pending-orders']) ? 'active' : '' }}">
                <a href="{{ url('/orders/pending') }}">
                    <i class="nav-icon fas fa-tasks"></i>
                    <span>Kelola Pesanan</span>
                </a>
            </li>
            
            @else
            <!-- Customer Order Menu -->
            <li class="nav-item {{ in_array($activeMenu, ['create-order']) ? 'active' : '' }}">
                <a href="{{ url('/orders/create') }}">
                    <i class="nav-icon fas fa-shopping-basket"></i>
                    <span>Buat Pesanan</span>
                </a>
            </li>

            <li class="nav-item {{ in_array($activeMenu, ['orders']) ? 'active' : '' }}">
                <a href="{{ url('/orders') }}">
                    <i class="nav-icon fas fa-history"></i>
                    <span>Riwayat Pesanan</span>
                </a>
            </li>
            @endif

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
                <a href="{{ url('/logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>