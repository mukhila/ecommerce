<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="multikart">
    <meta name="keywords" content="multikart">
    <meta name="author" content="multikart">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('frontassets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('frontassets/images/favicon.png') }}" type="image/x-icon">
    <title>@yield('title') - Multikart</title>

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


    <!-- Search Modal Start -->
    <div class="modal fade search-modal theme-modal-2" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">Search in store</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="search-input-box">
                        <input type="text" class="form-control" placeholder="Search with brands and categories...">
                        <i class="ri-search-2-line"></i>
                    </div>

                    <ul class="search-category">
                        <li class="category-title">Top search:</li>
                        <li>
                            <a href="category-page.html">Baby Essentials</a>
                        </li>
                        <li>
                            <a href="category-page.html">Bag Emporium</a>
                        </li>
                        <li>
                            <a href="category-page.html">Bags</a>
                        </li>
                        <li>
                            <a href="category-page.html">Books</a>
                        </li>
                    </ul>

                    <div class="search-product-box mt-sm-4 mt-3">
                        <h3 class="search-title">Most Searched</h3>

                        <div class="row row-cols-xl-4 row-cols-md-3 row-cols-2 g-sm-4 g-3">
                            <div class="col">
                                <div class="basic-product theme-product-1">
                                    <div class="overflow-hidden">
                                        <div class="img-wrapper">
                                            <div class="ribbon"><span>Exclusive</span></div>
                                            <a href="product-page(image-swatch).html">
                                                <img src="{{ asset('frontassets/images/fashion-1/product/1.jpg') }}"
                                                    class="img-fluid blur-up lazyloaded" alt="">
                                            </a>
                                            <div class="rating-label"><i class="ri-star-fill"></i><span>2.5</span>
                                            </div>
                                            <div class="cart-info">
                                                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                                                    <i class="ri-heart-line"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart"
                                                    title="Add to cart">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </button>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView"
                                                    title="Quick View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="compare.html" title="Compare">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            <div>
                                                <div class="brand-w-color">
                                                    <a class="product-title" href="product-page(accordian).html">
                                                        Glamour Gaze
                                                    </a>
                                                    <div class="color-panel">
                                                        <ul>
                                                            <li style="background-color: papayawhip;"></li>
                                                            <li style="background-color: burlywood;"></li>
                                                            <li style="background-color: gainsboro;"></li>
                                                        </ul>
                                                        <span>+2</span>
                                                    </div>
                                                </div>
                                                <h6>Boyfriend Shirts</h6>
                                                <h4 class="price">₹ 279<del> ₹300 </del><span
                                                        class="discounted-price"> 7%
                                                        Off
                                                    </span>
                                                </h4>
                                            </div>
                                            <ul class="offer-panel">
                                                <li>
                                                    <span class="offer-icon">
                                                        <i class="ri-discount-percent-fill"></i>
                                                    </span>
                                                    Limited Time Offer: 4% off
                                                </li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 4% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 4% off</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="basic-product theme-product-1">
                                    <div class="overflow-hidden">
                                        <div class="img-wrapper">
                                            <a href="product-page(accordian).html"><img
                                                    src="{{ asset('frontassets/images/fashion-1/product/11.jpg') }}"
                                                    class="img-fluid blur-up lazyloaded" alt=""></a>
                                            <div class="rating-label"><i class="ri-star-s-fill"></i>
                                                <span>6.5</span>
                                            </div>
                                            <div class="cart-info">
                                                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                                                    <i class="ri-heart-line"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart"
                                                    title="Add to cart">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </button>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView"
                                                    title="Quick View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="compare.html" title="Compare">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            <div>
                                                <div class="brand-w-color">
                                                    <a class="product-title" href="product-page(accordian).html">
                                                        VogueVista
                                                    </a>
                                                </div>
                                                <h6>Chic Crop Top</h6>
                                                <h4 class="price">₹ 560<del> ₹680 </del><span
                                                        class="discounted-price"> 5%
                                                        Off
                                                    </span>
                                                </h4>
                                            </div>
                                            <ul class="offer-panel">
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 25% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 25% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 25% off</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="basic-product theme-product-1">
                                    <div class="overflow-hidden">
                                        <div class="img-wrapper">
                                            <a href="product-page(accordian).html"><img
                                                    src="{{ asset('frontassets/images/fashion-1/product/15.jpg') }}"
                                                    class="img-fluid blur-up lazyloaded" alt=""></a>
                                            <div class="rating-label"><i class="ri-star-s-fill"></i>
                                                <span>3.7</span>
                                            </div>
                                            <div class="cart-info">
                                                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                                                    <i class="ri-heart-line"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart"
                                                    title="Add to cart">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </button>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView"
                                                    title="Quick View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="compare.html" title="Compare">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            <div>
                                                <div class="brand-w-color">
                                                    <a class="product-title" href="product-page(accordian).html">
                                                        Urban Chic
                                                    </a>
                                                </div>
                                                <h6>Classic Jacket</h6>
                                                <h4 class="price">₹ 380 </h4>
                                            </div>
                                            <ul class="offer-panel">
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 10% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 10% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 10% off</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="basic-product theme-product-1">
                                    <div class="overflow-hidden">
                                        <div class="img-wrapper">
                                            <a href="product-page(image-swatch).html">
                                                <img src="{{ asset('frontassets/images/fashion-1/product/16.jpg') }}"
                                                    class="img-fluid blur-up lazyloaded" alt="">
                                            </a>
                                            <div class="rating-label"><i class="ri-star-s-fill"></i>
                                                <span>8.7</span>
                                            </div>
                                            <div class="cart-info">
                                                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                                                    <i class="ri-heart-line"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart"
                                                    title="Add to cart">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </button>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView"
                                                    title="Quick View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="compare.html" title="Compare">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            <div>
                                                <div class="brand-w-color">
                                                    <a class="product-title" href="product-page(accordian).html">
                                                        Couture Edge
                                                    </a>
                                                </div>
                                                <h6>Versatile Shacket</h6>
                                                <h4 class="price"> ₹300
                                                </h4>
                                            </div>
                                            <ul class="offer-panel">
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 12% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 12% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 12% off</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Modal End -->


    <!-- Cart Offcanvas Start -->
    <div class="offcanvas offcanvas-end cart-offcanvas" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header">
            <h3 class="offcanvas-title">My Cart ({{ $sharedCartCount }})</h3>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            @if($sharedCartCount > 0)
                @php
                    $freeShippingThreshold = 3000; // ₹3000 for free shipping
                    $remaining = $freeShippingThreshold - $sharedCartTotal;
                    $progressPercentage = min(($sharedCartTotal / $freeShippingThreshold) * 100, 100);
                @endphp

                @if($remaining > 0)
                    <div class="pre-text-box">
                        <p>Spend ₹{{ number_format($remaining, 2) }} More And Enjoy Free Shipping!</p>
                        <div class="progress" role="progressbar">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $progressPercentage }}%;">
                                <i class="ri-truck-line"></i>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="pre-text-box">
                        <p class="text-success"><i class="ri-check-line"></i> You qualify for FREE shipping!</p>
                    </div>
                @endif

                <div class="sidebar-title">
                    <a href="javascript:void(0)" onclick="clearCart()">Clear Cart</a>
                </div>

                <div class="cart-media">
                    <ul class="cart-product">
                        @foreach($sharedCartItems as $item)
                            <li>
                                <div class="media">
                                    <a href="{{ route('product.show', $item->product->slug) }}">
                                        @if($item->product->images->count() > 0)
                                            <img src="{{ asset('uploads/' . $item->product->images->first()->image_path) }}"
                                                 class="img-fluid"
                                                 alt="{{ $item->product->name }}">
                                        @else
                                            <img src="{{ asset('frontassets/images/product-placeholder.jpg') }}"
                                                 class="img-fluid"
                                                 alt="{{ $item->product->name }}">
                                        @endif
                                    </a>
                                    <div class="media-body">
                                        <a href="{{ route('product.show', $item->product->slug) }}">
                                            <h4>{{ $item->product->name }}</h4>
                                        </a>
                                        @if($item->attributes)
                                            <div class="mt-1 mb-2">
                                                @foreach($item->attributes as $attrName => $attrData)
                                                    <small class="text-muted d-block">
                                                        <strong>{{ $attrName }}:</strong> {{ $attrData['value'] ?? $attrData }}
                                                    </small>
                                                @endforeach
                                            </div>
                                        @endif
                                        <h4 class="quantity">
                                            <span>{{ $item->quantity }} x ₹{{ number_format($item->price, 2) }}</span>
                                        </h4>

                                        <div class="qty-box">
                                            <div class="input-group qty-container">
                                                <button class="btn quantity-left-minus"
                                                        onclick="updateCartItemOffcanvas({{ $item->id }}, {{ $item->quantity - 1 }})">
                                                    <i class="ri-subtract-line"></i>
                                                </button>
                                                <input type="number" readonly name="qty"
                                                       class="form-control input-qty input-number"
                                                       value="{{ $item->quantity }}"
                                                       data-item-id="{{ $item->id }}"
                                                       max="{{ $item->product->stock }}">
                                                <button class="btn quantity-right-plus"
                                                        onclick="updateCartItemOffcanvas({{ $item->id }}, {{ $item->quantity + 1 }})">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="close-circle">
                                            <button class="close_button delete-button"
                                                    onclick="removeCartItem({{ $item->id }})"
                                                    type="button">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <ul class="cart_total">
                        <li>
                            <div class="total">
                                <h5>Sub Total : <span id="offcanvas-cart-total">₹{{ number_format($sharedCartTotal, 2) }}</span></h5>
                            </div>
                        </li>
                        <li>
                            <div class="buttons">
                                <a href="{{ route('cart.index') }}" class="btn view-cart">View Cart</a>
                                @auth
                                    <a href="{{ route('checkout.index') }}" class="btn checkout">Check Out</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn checkout">Login to Checkout</a>
                                @endauth
                            </div>
                        </li>
                    </ul>
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('frontassets/images/icon-empty-cart.png') }}"
                         class="img-fluid mb-3"
                         alt="Empty Cart"
                         style="max-width: 150px;">
                    <h5>Your cart is empty</h5>
                    <p class="text-muted">Add items to get started</p>
                    <a href="{{ route('home') }}" class="btn btn-solid mt-3">Continue Shopping</a>
                </div>
            @endif
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
    <div class="cookie-bar">
        <p>We use cookies to improve our site and your shopping experience. By continuing to browse our site you accept
            our cookie policy.</p>
        <a href="#!" class="btn btn-solid btn-xs">accept</a>
        <a href="#!" class="btn btn-solid btn-xs">decline</a>
    </div>
    <!-- cookie bar end -->

   

    <!-- Quick-view modal popup start-->
    <div class="modal fade theme-modal-2 quick-view-modal" id="quickView">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
                <div class="modal-body">
                    <div class="wrap-modal-slider">
                        <div class="row g-sm-4 g-3">
                            <div class="col-lg-6">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="view-main-slider">
                                            <div>
                                                <img src="{{ asset('frontassets/images/fashion-1/product/1.jpg') }}" class="img-fluid"
                                                    alt="">
                                            </div>
                                            <div>
                                                <img src="{{ asset('frontassets/images/fashion-1/product/1-1.jpg') }}" class="img-fluid"
                                                    alt="">
                                            </div>
                                            <div>
                                                <img src="{{ asset('frontassets/images/fashion-1/product/1-2.jpg') }}" class="img-fluid"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="view-thumbnail-slider no-arrow">
                                            <div>
                                                <div class="slider-image">
                                                    <img src="{{ asset('frontassets/images/fashion-1/product/1.jpg') }}"
                                                        class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="slider-image">
                                                    <img src="{{ asset('frontassets/images/fashion-1/product/1-1.jpg') }}"
                                                        class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="slider-image">
                                                    <img src="{{ asset('frontassets/images/fashion-1/product/1-2.jpg') }}"
                                                        class="img-fluid" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="right-sidebar-modal">
                                    <a class="name" href="product-page(accordian).html">Boyfriend Shirts</a>
                                    <div class="product-rating">
                                        <ul class="rating-list">
                                            <li>
                                                <i class="ri-star-line"></i>
                                            </li>
                                            <li>
                                                <i class="ri-star-line"></i>
                                            </li>
                                            <li>
                                                <i class="ri-star-line"></i>
                                            </li>
                                            <li>
                                                <i class="ri-star-line"></i>
                                            </li>
                                            <li>
                                                <i class="ri-star-line"></i>
                                            </li>
                                        </ul>
                                        <div class="divider">|</div>
                                        <a href="#!">0 Review</a>
                                    </div>
                                    <div class="price-text">
                                        <h3>
                                            <span class="fw-normal">MRP:</span>
                                            ₹1056
                                            <del>₹1200</del>
                                            <span class="discounted-price">12% off</span>
                                        </h3>
                                        <span class="text">Inclusive all the text</span>
                                    </div>
                                    <p class="description-text">Boyfriend shirts are oversized, relaxed-fit shirts
                                        originally inspired by men's fashion. They offer a comfortable and effortlessly
                                        chic look, often characterized by a loose silhouette and rolled-up sleeves.
                                        Perfect for a casual yet stylish vibe</p>
                                    <div class="qty-box">
                                        <div class="input-group qty-container">
                                            <button class="btn qty-btn-minus">
                                                <i class="ri-arrow-left-s-line"></i>
                                            </button>
                                            <input type="number" readonly="" name="qty" class="form-control input-qty"
                                                value="1">
                                            <button class="btn qty-btn-plus">
                                                <i class="ri-arrow-right-s-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-buy-btn-group">
                                        <button
                                            class="btn btn-animation btn-solid buy-button hover-solid scroll-button">
                                            <span class="d-inline-block ring-animation">
                                                <i class="ri-shopping-cart-line me-1"></i>
                                            </span>
                                            Add To Cart
                                        </button>
                                        <button class="btn btn-solid buy-button">Buy Now</button>
                                    </div>

                                    <div class="buy-box compare-box">
                                        <a href="#!">
                                            <i class="ri-heart-line"></i>
                                            <span>Add To Wishlist</span>
                                        </a>
                                        <a href="#!">
                                            <i class="ri-refresh-line"></i>
                                            <span>Add To Compare</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick-view modal popup end-->



    <!-- Add to cart modal popup start-->
    <div class="modal fade bd-example-modal-lg theme-modal cart-modal" id="addtocart" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body modal1">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="modal-bg addtocart">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                    <div class="media">
                                        <a href="#!">
                                            <img class="img-fluid blur-up lazyload pro-img"
                                                src="{{ asset('frontassets/images/fashion/product/55.jpg') }}" alt="">
                                        </a>
                                        <div class="media-body align-self-center text-center">
                                            <a href="#!">
                                                <h6>
                                                    <i class="ri-checkbox-circle-fill"></i>Item
                                                    <span>men full sleeves</span>
                                                    <span> successfully added to your Cart</span>
                                                </h6>
                                            </a>
                                            <div class="buttons">
                                                <a href="#!" class="view-cart btn btn-solid">Your cart</a>
                                                <a href="#!" class="checkout btn btn-solid">Check out</a>
                                                <a href="#!" class="continue btn btn-solid">Continue shopping</a>
                                            </div>

                                            <div class="upsell_payment">
                                                <img src="{{ asset('frontassets/images/payment_cart.png') }}"
                                                    class="img-fluid blur-up lazyload" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-section">
                                        <div class="col-12 product-upsell text-center">
                                            <h4>Customers who bought this item also.</h4>
                                        </div>
                                        <div class="row" id="upsell_product">
                                            <div class="product-box col-sm-3 col-6">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="#!">
                                                            <img src="{{ asset('frontassets/images/fashion/product/1.jpg') }}"
                                                                class="img-fluid blur-up lazyload mb-1"
                                                                alt="cotton top">
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <h6><a href="#!"><span>cotton top</span></a></h6>
                                                        <h4><span>₹2500</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-box col-sm-3 col-6">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="#!">
                                                            <img src="{{ asset('frontassets/images/fashion/product/34.jpg') }}"
                                                                class="img-fluid blur-up lazyload mb-1"
                                                                alt="cotton top">
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <h6><a href="#!"><span>cotton top</span></a></h6>
                                                        <h4><span>₹2500</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-box col-sm-3 col-6">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="#!">
                                                            <img src="{{ asset('frontassets/images/fashion/product/13.jpg') }}"
                                                                class="img-fluid blur-up lazyload mb-1"
                                                                alt="cotton top">
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <h6><a href="#!"><span>cotton top</span></a></h6>
                                                        <h4><span>₹2500</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-box col-sm-3 col-6">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="#!">
                                                            <img src="{{ asset('frontassets/images/fashion/product/19.jpg') }}"
                                                                class="img-fluid blur-up lazyload mb-1"
                                                                alt="cotton top">
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <h6><a href="#!"><span>cotton top</span></a></h6>
                                                        <h4><span>₹2500</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add to cart modal popup end-->


    <!-- exit modal popup start-->
    <div class="modal fade bd-example-modal-lg theme-modal exit-modal" id="exit_popup" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body modal1">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="modal-bg">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        <i class="ri-close-line"></i>
                                    </button>
                                    <div class="media">
                                        <img src="{{ asset('frontassets/images/stop.png') }}"
                                            class="stop img-fluid blur-up lazyload me-3" alt="">
                                        <div class="media-body text-start align-self-center">
                                            <div>
                                                <h2>wait!</h2>
                                                <h4>We want to give you
                                                    <b>10% discount</b>
                                                    <span>for your first order</span>
                                                </h4>
                                                <h5>Use discount code at checkout</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add to cart modal popup end-->


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