<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-logged-in" content="{{ auth()->check() ? 'true' : 'false' }}">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('frontassets/images/apple-icon-57x57.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('frontassets/images/apple-icon-60x60.png') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('frontassets/images/apple-icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('frontassets/images/apple-icon-76x76.png') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('frontassets/images/apple-icon-114x114.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('frontassets/images/apple-icon-120x120.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('frontassets/images/apple-icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('frontassets/images/apple-icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontassets/images/apple-icon-180x180.png') }}">
<link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('frontassets/images/android-icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontassets/images/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('frontassets/images/favicon-96x96.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontassets/images/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('frontassets/images/manifest.json') }}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{ asset('frontassets/images/ms-icon-144x144.png') }}">
<meta name="theme-color" content="#ffffff">

    <!-- Primary SEO -->
    <title>{{ isset($seo_data) && $seo_data->title ? $seo_data->title : 'Jango Kidswear | Premium Kids Fashion Online' }}</title>
    <meta name="description" content="{{ isset($seo_data) && $seo_data->description ? $seo_data->description : 'Jango Kidswear - Shop premium, stylish and affordable kids clothing online. Wide range of boys, girls and baby fashion. Free shipping above ₹3000. COD available.' }}">
    <meta name="keywords" content="{{ isset($seo_data) && $seo_data->keywords ? $seo_data->keywords : 'kids fashion, children clothing, boys clothes, girls clothes, baby clothes, kids wear, children apparel online India, Jango Kidswear' }}">
    <meta name="author" content="Jango Kidswear">
    <meta name="robots" content="{{ isset($seo_data) && $seo_data->robots ? $seo_data->robots : 'index, follow' }}">
    <link rel="canonical" href="{{ isset($seo_data) && $seo_data->canonical_url ? $seo_data->canonical_url : url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ isset($seo_data) && $seo_data->type ? $seo_data->type : 'website' }}">
    <meta property="og:site_name" content="Jango Kidswear">
    <meta property="og:locale" content="en_IN">
    <meta property="og:title" content="{{ isset($seo_data) && $seo_data->title ? $seo_data->title : 'Jango Kidswear | Premium Kids Fashion Online' }}">
    <meta property="og:description" content="{{ isset($seo_data) && $seo_data->description ? $seo_data->description : 'Shop premium kids clothing online at Jango Kidswear. Free shipping above ₹3000.' }}">
    <meta property="og:image" content="{{ isset($seo_data) && $seo_data->image ? asset($seo_data->image) : asset('frontassets/images/logo.png') }}">
    <meta property="og:url" content="{{ isset($seo_data) && $seo_data->canonical_url ? $seo_data->canonical_url : url()->current() }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@@jangokidswear">
    <meta name="twitter:title" content="@yield('og_title', 'Jango Kidswear | Premium Kids Fashion Online')">
    <meta name="twitter:description" content="@yield('og_description', 'Shop premium kids clothing online at Jango Kidswear.')">
    <meta name="twitter:image" content="@yield('og_image', asset('frontassets/images/logo.png'))">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('frontassets/images/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('frontassets/images/favicon.ico') }}" type="image/x-icon">

    <!-- JSON-LD Structured Data (default Organization schema) -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Jango Kidswear",
        "url": "{{ config('app.url') }}",
        "logo": "{{ asset('frontassets/images/logo.png') }}",
        "sameAs": [],
        "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "customer service",
            "availableLanguage": "English"
        }
    }
    </script>
    <!-- Page-specific JSON-LD (product schema, website schema, etc.) -->
    @stack('json_ld')

    <!--Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">

    <!-- Icons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/vendors/font-awesome.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">

    <!-- Page-specific styles -->
    @stack('styles')

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
    <link rel="stylesheet" type="text/css" href="{{ asset('frontassets/css/newui.css') }}">
</head>

<body class="theme-color-1">


    @include('layouts.loader')

    @include('layouts.top_bar')

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

@stack('scripts')



    <!-- Search Modal Start -->
    <div class="modal fade search-modal theme-modal-2" id="searchModal" tabindex="-1" aria-label="Search">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
                <div class="modal-body p-4">
                    <form action="{{ route('search.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text"
                                   name="q"
                                   id="searchModalInput"
                                   class="form-control form-control-lg"
                                   placeholder="Search for kids dresses, tops, party wear…"
                                   autocomplete="off">
                            <button class="btn btn-solid" type="submit">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Modal End -->

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


    <!-- ── PWA Install Banner ──────────────────────────────────── -->
    <div id="pwaInstallBanner" class="pwa-banner" role="dialog" aria-label="Install JangoKids App" hidden>
        <div class="pwa-banner-inner">
            <div class="pwa-banner-icon">🛍️</div>
            <div class="pwa-banner-text">
                <strong>Install JangoKids</strong>
                <span>Shop faster — add to home screen</span>
            </div>
            <button class="pwa-install-btn" id="pwaInstallBtn" aria-label="Install app">Install</button>
            <button class="pwa-dismiss-btn" id="pwaDismissBtn" aria-label="Dismiss">✕</button>
        </div>
    </div>

    <!-- iOS-specific hint banner -->
    <div id="pwaIosBanner" class="pwa-banner pwa-ios-banner" role="dialog" aria-label="Add to Home Screen" hidden>
        <div class="pwa-banner-inner">
            <div class="pwa-banner-icon">🛍️</div>
            <div class="pwa-banner-text">
                <strong>Add to Home Screen</strong>
                <span>Tap <strong>Share</strong> then <strong>Add to Home Screen</strong></span>
            </div>
            <button class="pwa-dismiss-btn" id="pwaIosDismissBtn" aria-label="Dismiss">✕</button>
        </div>
    </div>
    <!-- ── /PWA Install Banner ─────────────────────────────────── -->

    <!-- cookie bar start -->
    <div class="cookie-bar" id="cookieBar" style="display: none;">
        <p>We use cookies to improve our site and your shopping experience. By continuing to browse our site you accept
            our cookie policy.</p>
        <a href="javascript:void(0)" class="btn btn-solid btn-xs" onclick="acceptCookies()">Accept</a>
        <a href="javascript:void(0)" class="btn btn-solid btn-xs" onclick="declineCookies()">Decline</a>
    </div>
    <!-- cookie bar end -->

   







    

    <!-- ── PWA styles ──────────────────────────────────── -->
    <style>
    .pwa-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        background: #fff;
        box-shadow: 0 -4px 24px rgba(0,0,0,.13);
        border-top: 3px solid #FF4757;
        transform: translateY(100%);
        transition: transform .35s cubic-bezier(.4,0,.2,1);
        padding: 14px 16px;
    }
    .pwa-banner:not([hidden]) {
        transform: translateY(0);
    }
    .pwa-banner-inner {
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 540px;
        margin: 0 auto;
    }
    .pwa-banner-icon {
        font-size: 28px;
        flex-shrink: 0;
        line-height: 1;
    }
    .pwa-banner-text {
        flex: 1;
        min-width: 0;
    }
    .pwa-banner-text strong {
        display: block;
        font-size: 14px;
        font-weight: 800;
        color: #1A1F36;
        line-height: 1.2;
    }
    .pwa-banner-text span {
        font-size: 12px;
        color: #6B7280;
        display: block;
        margin-top: 2px;
    }
    .pwa-install-btn {
        flex-shrink: 0;
        padding: 9px 20px;
        background: linear-gradient(135deg, #FF4757, #FF6348);
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        font-family: inherit;
        white-space: nowrap;
        box-shadow: 0 4px 14px rgba(255,71,87,.35);
        transition: transform .2s, box-shadow .2s;
    }
    .pwa-install-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(255,71,87,.45);
    }
    .pwa-dismiss-btn {
        flex-shrink: 0;
        width: 30px;
        height: 30px;
        background: #F3F4F6;
        border: none;
        border-radius: 50%;
        font-size: 13px;
        color: #6B7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .2s;
        padding: 0;
    }
    .pwa-dismiss-btn:hover {
        background: #E5E7EB;
        color: #1A1F36;
    }
    .pwa-ios-banner .pwa-banner-text strong { font-size: 13px; }
    @media (min-width: 768px) {
        .pwa-banner { padding: 14px 32px; }
    }
    </style>

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

    <!-- Wishlist js-->
    <script src="{{ asset('js/wishlist.js') }}"></script>

    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('#exampleModal').modal('show');
            }, 2500);
        });

        // Auto-focus search input when modal opens
        const searchModal = document.getElementById('searchModal');
        if (searchModal) {
            searchModal.addEventListener('shown.bs.modal', function () {
                document.getElementById('searchModalInput').focus();
            });
        }

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

    <!-- ── PWA Service Worker + Install Logic ───────────────── -->
    <script>
    (function () {
        // ── 1. Register Service Worker ────────────────────────
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js', { scope: '/' })
                    .catch(function (err) { console.warn('SW registration failed:', err); });
            });
        }

        // ── 2. Dismiss helpers ────────────────────────────────
        var DISMISS_KEY  = 'jk_pwa_dismissed';
        var DISMISS_DAYS = 7;

        function wasDismissed() {
            var ts = localStorage.getItem(DISMISS_KEY);
            if (!ts) return false;
            return (Date.now() - parseInt(ts, 10)) < DISMISS_DAYS * 864e5;
        }
        function saveDismiss() {
            localStorage.setItem(DISMISS_KEY, Date.now().toString());
        }

        function showBanner(id) {
            var el = document.getElementById(id);
            if (el) { el.hidden = false; }
        }
        function hideBanner(id) {
            var el = document.getElementById(id);
            if (el) { el.hidden = true; }
        }

        // ── 3. Android / Chrome – beforeinstallprompt ─────────
        var deferredPrompt = null;

        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            if (wasDismissed()) return;
            deferredPrompt = e;

            // Show banner after a short delay so page load feels complete
            setTimeout(function () { showBanner('pwaInstallBanner'); }, 2500);
        });

        document.addEventListener('DOMContentLoaded', function () {
            var installBtn  = document.getElementById('pwaInstallBtn');
            var dismissBtn  = document.getElementById('pwaDismissBtn');
            var iosDismiss  = document.getElementById('pwaIosDismissBtn');

            // Install button clicked
            if (installBtn) {
                installBtn.addEventListener('click', function () {
                    hideBanner('pwaInstallBanner');
                    if (!deferredPrompt) return;
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(function (choice) {
                        if (choice.outcome === 'accepted') {
                            saveDismiss();
                        }
                        deferredPrompt = null;
                    });
                });
            }

            // Dismiss button (Android)
            if (dismissBtn) {
                dismissBtn.addEventListener('click', function () {
                    hideBanner('pwaInstallBanner');
                    saveDismiss();
                });
            }

            // Dismiss button (iOS)
            if (iosDismiss) {
                iosDismiss.addEventListener('click', function () {
                    hideBanner('pwaIosBanner');
                    saveDismiss();
                });
            }

            // ── 4. iOS Safari hint ────────────────────────────
            var isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);
            var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
            var isStandalone = ('standalone' in navigator) && navigator.standalone;

            if (isIos && isSafari && !isStandalone && !wasDismissed()) {
                setTimeout(function () { showBanner('pwaIosBanner'); }, 3000);
            }
        });

        // ── 5. Hide banner once installed ─────────────────────
        window.addEventListener('appinstalled', function () {
            hideBanner('pwaInstallBanner');
            saveDismiss();
        });
    }());
    </script>
    <!-- ── /PWA ──────────────────────────────────────────────── -->

</body>

</html>