<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button type="button" class="button-toggle-menu nav-link">
                        <iconify-icon icon="solar:hamburger-menu-linear" class="fs-22 align-middle text-dark"></iconify-icon>
                    </button>
                </li>
                <li class="d-none d-lg-block">
                    <form class="app-search d-none d-md-block me-auto">
                        <div class="position-relative topbar-search">
                            <iconify-icon icon="solar:minimalistic-magnifer-line-duotone"
                                class="fs-18 align-middle text-dark position-absolute text-dark top-50 translate-middle-y ms-2">
                            </iconify-icon>
                            <input type="text" class="form-control shadow-none" placeholder="Search..." />
                        </div>
                    </form>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center gap-2">

                <!-- Light/Dark Mode Button Themes -->
                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link d-flex align-items-center justify-content-center"
                        id="light-dark-mode">
                        <div class="d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:sun-2-bold-duotone" class="fs-22 text-dark align-middle dark-mode"></iconify-icon>
                            <iconify-icon icon="solar:moon-bold-duotone" class="fs-22 text-dark align-middle light-mode"></iconify-icon>
                        </div>
                    </button>
                </li>

                <!-- User Dropdown -->
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('adminassets/images/users/avatar/avatar-1.svg') }}" alt="user-image" class="img-fluid " />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <!-- item-->
                        <div class="dropdown-header noti-title border-bottom border-dashed d-flex align-items-center">
                            <img src="{{ asset('adminassets/images/users/avatar/avatar-1.svg') }}" alt="user-image" class="avatar avatar-xs rounded-circle me-2" />
                            <h6 class="text-overflow m-0">Welcome {{ session('admin_name') }}!</h6>
                        </div>

                        <!-- item-->
                        <a href="{{ route('admin.profile') }}" class="dropdown-item notify-item border-bottom border-dashed">
                            <iconify-icon icon="solar:user-bold-duotone" class="fs-18 align-middle"></iconify-icon>
                            <span>My Account</span>
                        </a>
                         <a class="dropdown-item notify-item border-bottom border-dashed" href="{{ route('admin.password.change') }}"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> Change Password</a>

                        <!-- item-->
                        <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
                            <iconify-icon icon="solar:logout-2-bold-duotone" class="fs-18 align-middle"></iconify-icon>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
