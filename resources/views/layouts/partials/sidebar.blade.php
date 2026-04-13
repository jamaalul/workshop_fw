<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="../assets/images/faces/face1.jpg" alt="profile" />
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="d-flex flex-column nav-profile-text">
                    <span class="mb-2 font-weight-bold">{{ Auth::user()->name }}</span>
                    <span class="text-secondary text-small">Project Manager</span>
                </div>
                <i class="text-success mdi mdi-bookmark-check nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-bookshelf menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('barang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang') }}">
                <span class="menu-title">Barang</span>
                <i class="fa fa-inbox menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('jquery/html-table*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('jquery.html-table') }}">
                <span class="menu-title">JQuery HTML Table</span>
                <i class="mdi mdi-table menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('jquery/datatables*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('jquery.datatables') }}">
                <span class="menu-title">JQuery DataTables</span>
                <i class="mdi mdi-table-large menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('jquery/select*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('jquery.select') }}">
                <span class="menu-title">JQuery Select</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('wilayah/jquery') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('wilayah.jquery') }}">
                <span class="menu-title">Wilayah (AJAX)</span>
                <i class="mdi mdi-map-marker menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('wilayah/axios*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('wilayah.axios') }}">
                <span class="menu-title">Wilayah (AXIOS)</span>
                <i class="mdi mdi-map-marker menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('pos/jquery*') && !request()->is('pos/*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pos.jquery') }}">
                <span class="menu-title">POS (AJAX)</span>
                <i class="mdi mdi-cart menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('pos/axios*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pos.axios') }}">
                <span class="menu-title">POS (AXIOS)</span>
                <i class="mdi mdi-cart menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('canteen*') || request()->is('payment*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('canteen.index') }}">
                <span class="menu-title">Mini Canteen</span>
                <i class="mdi mdi-food menu-icon"></i>
            </a>
        </li>
        <li class="nav-item pt-3">
            <span class="nav-link text-secondary text-uppercase font-weight-bold">Vendor Dashboard</span>
        </li>
        <li class="nav-item {{ request()->is('vendor/orders*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendor.orders') }}">
                <span class="menu-title">Paid Orders</span>
                <i class="mdi mdi-currency-usd menu-icon"></i>
            </a>
        </li>

        {{-- ── Customer Menu (with submenu) ── --}}
        <li class="nav-item {{ request()->is('customer*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#customerMenu" role="button"
               aria-expanded="{{ request()->is('customer*') ? 'true' : 'false' }}"
               aria-controls="customerMenu">
                <span class="menu-title">Customer</span>
                <i class="mdi mdi-account-multiple menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('customer*') ? 'show' : '' }}" id="customerMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}"
                           href="{{ route('customer.index') }}">
                            <i class="mdi mdi-account-group me-1"></i> Data Customer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.create.blob') ? 'active' : '' }}"
                           href="{{ route('customer.create.blob') }}">
                            <i class="mdi mdi-database me-1"></i> Tambah Customer 1 (Blob)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.create.file') ? 'active' : '' }}"
                           href="{{ route('customer.create.file') }}">
                            <i class="mdi mdi-folder-image me-1"></i> Tambah Customer 2 (File)
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>

