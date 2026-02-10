    <!-- header start -->
    <header>
    @include('layouts.top_bar')
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="main-menu">
                        <div class="menu-left">
                            @include('layouts.sidebar_menu')
                        </div>
                        <div class="menu-right pull-right">
                            <div>
                                @include('layouts.navigation')
                            </div>
                            <div>
                                <div class="icon-nav">
                                    <ul>
         
                                   
                                        <li class="onhover-div mobile-account">
                                            <div><i class="ri-user-line"></i></div>
                                            <div class="show-div setting">
                                                @guest
                                                <h6>Account</h6>
                                                <ul>
                                                    <li><a href="{{ route('login') }}">Login</a></li>
                                                    <li><a href="{{ route('register') }}">Register</a></li>
                                                </ul>
                                                @else
                                                <h6>{{ Auth::user()->name }}</h6>
                                                <ul>
                                                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                                     <li><a href="{{ route('dashboard') }}">Wishlist</a></li>
                                                    <li>
                                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                    </li>
                                                </ul>
                                                @endguest
                                            </div>
                                        </li>
                                        <li class="onhover-div mobile-cart">

                                            <div data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                                                <i class="ri-shopping-cart-line"></i>
                                            </div>
                                            <span class="cart_qty_cls">2</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header end -->
