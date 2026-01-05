<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <span class="logo-txt">Venix</span>
                    </span>
                    <span class="logo-lg">
                        <span class="logo-txt">Venix</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">Admin</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile</a>
                    <a class="dropdown-item" href="{{ route('admin.password.change') }}"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> Change Password</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
