<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Jango Kids">
    <meta name="keywords" content="Jango Kids">
    <meta name="author" content="Jango Kids">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('frontassets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('frontassets/images/favicon.png') }}" type="image/x-icon">
    <title>@yield('title') - Jango Kids</title>

    <!--Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">

    <!-- Icons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/vendors/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/vendors/remixicon.css') }}">

    <!-- Slick slider css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/vendors/slick.css') }}">

    <!-- Animate icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/vendors/animate.css') }}">

    <!-- Themify icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/vendors/themify-icons.css') }}">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/vendors/bootstrap.css') }}">

    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/custom.css') }}">
</head>

<body class="theme-color-1">


    @include('layouts.loader')


    @include('layouts.header')

    {{-- Flash Messages --}}
    @if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Warning!</strong> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Info:</strong> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Validation Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    @endif

    @yield('content')
    @include('layouts.footer')

@stack('styles')
 @stack('scripts')



    <!-- Cart Offcanvas Start -->
    <div class="offcanvas offcanvas-end cart-offcanvas" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header">
            <h3 class="offcanvas-title">My Cart (<span id="offcanvas-cart-count">{{ $sharedCartCount }}</span>)</h3>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="offcanvas-body" id="cart-offcanvas-body">
            @include('cart.partials.offcanvas-content', [
                'cartItems' => $sharedCartItems,
                'cartCount' => $sharedCartCount,
                'cartTotal' => $sharedCartTotal
            ])
        </div>
    </div>

    <div class="modal fade theme-modal-2 variation-modal" id="variationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
                <div class="modal-body">
                    <div class="product-right product-page-details variation-title">
                        <h2 class="main-title">
                            <a href="product-page(accordian).html">Cami Tank Top (Blue)</a>
                        </h2>
                        <h3 class="price-detail">₹1425 <span>5% off</span></h3>
                    </div>
                    <div class="variation-box">
                        <h4 class="sub-title">Color:</h4>
                        <ul class="quantity-variant color">
                            <li class="bg-light">
                                <span style="background-color: rgb(240, 0, 0);"></span>
                            </li>
                            <li class="bg-light">
                                <span style="background-color: rgb(47, 147, 72);"></span>
                            </li>
                            <li class="bg-light active">
                                <span style="background-color: rgb(0, 132, 255);"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="variation-qty-button">
                        <div class="qty-section">
                            <div class="qty-box">
                                <div class="input-group qty-container">
                                    <button class="btn qty-btn-minus">
                                        <i class="ri-subtract-line"></i>
                                    </button>
                                    <input type="number" readonly name="qty" class="form-control input-qty" value="1">
                                    <button class="btn qty-btn-plus">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="product-buttons">
                            <button class="btn btn-animation btn-solid hover-solid scroll-button"
                                id="replacecartbtnVariation14" type="submit" data-bs-dismiss="modal">
                                <i class="ri-shopping-cart-line me-1"></i>
                                Update Item
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Offcanvas End -->


    <!-- cookie bar start -->
    <div class="cookie-bar" id="cookieBar" style="display: none;">
        <p>We use cookies to improve our site and your shopping experience. By continuing to browse our site you accept
            our cookie policy.</p>
        <a href="javascript:void(0)" class="btn btn-solid btn-xs" onclick="acceptCookies()">Accept</a>
        <a href="javascript:void(0)" class="btn btn-solid btn-xs" onclick="declineCookies()">Decline</a>
    </div>
    <!-- cookie bar end -->

   







    

    <!-- facebook chat section start -->
    <!-- <div id="fb-root"></div>
    <script>
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src =
                'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script> -->
    <!-- Your customer chat code -->
    <!-- <div class="fb-customerchat" attribution=setup_tool page_id="2123438804574660" theme_color="#0084ff"
        logged_in_greeting="Hi! Welcome to PixelStrap Themes  How can we help you?"
        logged_out_greeting="Hi! Welcome to PixelStrap Themes  How can we help you?">
    </div> -->
    <!-- facebook chat section end -->


    <!-- tap to top -->
    <div class="tap-top top-cls">
        <div>
            <i class="ri-arrow-up-double-line"></i>
        </div>
    </div>
    <!-- tap to top end -->


    <!-- latest jquery-->
    <script src="{{ asset('frontassets/js/jquery-3.3.1.min.js') }}"></script>

    <!-- fly cart ui jquery-->
    <script src="{{ asset('frontassets/js/jquery-ui.min.js') }}"></script>

    <!-- exitintent jquery-->
    <script src="{{ asset('frontassets/js/jquery.exitintent.js') }}"></script>
    <script src="{{ asset('frontassets/js/exit.js') }}"></script>

    <!-- slick js-->
    <script src="{{ asset('frontassets/js/slick.js') }}"></script>

    <!-- menu js-->
    <script src="{{ asset('frontassets/js/menu.js') }}"></script>

    <!-- lazyload js-->
    <script src="{{ asset('frontassets/js/lazysizes.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('frontassets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Bootstrap Notification js-->
    <script src="{{ asset('frontassets/js/bootstrap-notify.min.js') }}"></script>

    <!-- Fly cart js-->
    <script src="{{ asset('frontassets/js/fly-cart.js') }}"></script>

    <!-- Theme js-->
    <script src="{{ asset('frontassets/js/theme-setting.js') }}"></script>
    <script src="{{ asset('frontassets/js/script.js') }}"></script>

    <!-- Cart js-->
    <script src="{{ asset('js/cart.js') }}"></script>

    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('#exampleModal').modal('show');
            }, 2500);
        });

        // Auto-dismiss flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.notification-container .alert)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

</body>

</html>