<nav id="sidebar" class="sidebar js-sidebar" style="box-shadow: 1rem 0rem 1rem -1rem rgba(0, 0, 0, 0.15) !important;">
    <div class="sidebar-content js-simplebar shadow" style="box-shadow: 1.5rem 0rem 1.5rem -1rem rgba(0, 0, 0, 0.15) !important;">
        <a class="sidebar-brand" href="{{ url('dashboard') }}">
            <div class="d-flex justify-content-center"><img id="logo" src="{{ asset('images/logo.png') }}"
                    style="width: 130px; height:130px" alt="Logo"></div>
        </a>
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                &nbsp;
            </li>
            <li class="sidebar-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ url('/dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::is('stok_barang') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ url('stok_barang') }}">
                    <i class="fa-solid fa-box-archive align-middle"></i> <span class="align-middle">Stok Barang</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
