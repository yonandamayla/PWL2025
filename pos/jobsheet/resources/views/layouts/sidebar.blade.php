<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- User Management -->
            <li class="nav-header">Manajemen Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/users') }}"
                    class="nav-link {{ in_array($activeMenu, ['users', 'roles']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Daftar Pengguna</p>
                </a>
            </li>

            <!-- Item Management -->
            <li class="nav-header">Barang</li>
            <li class="nav-item">
                <a href="{{ url('/items') }}"
                    class="nav-link {{ in_array($activeMenu, ['item-types', 'items', 'items-import']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>Daftar Barang</p>
                </a>
            </li>

            <!-- Order Management -->
            <li class="nav-header">Pesanan</li>
            <li class="nav-item">
                <a href="#"
                    class="nav-link {{ in_array($activeMenu, ['orders', 'create-order', 'orders-export']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>
                        Pesanan
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/orders/create') }}"
                            class="nav-link {{ ($activeMenu == 'create-order') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Buat Pesanan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/orders') }}" class="nav-link {{ ($activeMenu == 'orders') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>Daftar Transaksi</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- User Profile -->
            <li class="nav-header">Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/profile') }}" class="nav-link {{ ($activeMenu == 'profile') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-circle"></i>
                    <p>Profil</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/logout') }}" class="nav-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Keluar</p>
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>