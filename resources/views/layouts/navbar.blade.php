<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">


            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle"  data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('admin/images/profile_picture.jpg') }}" class="avatar img-fluid rounded me-1"
                        alt="Administrator" /> <span class="text-dark">{{ Auth()->user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <form action="{{ url('logout') }}" method="post">
                        @csrf
                        <button class="dropdown-item" type="submit">Log out</button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
