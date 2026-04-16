@extends('layouts.master')

@section('title', $product->name)
@section('meta_description', Str::limit(strip_tags($product->description ?? $product->name . ' - Shop this product at JangaKids. Premium quality kids fashion at affordable prices.'), 155))
@section('meta_keywords', $product->name . ', ' . ($product->category->name ?? 'kids fashion') . ', buy online, JangaKids')
@section('og_type', 'product')
@section('og_title', $product->name . ' | JangaKids')
@section('og_description', Str::limit(strip_tags($product->description ?? $product->name . ' - Available at JangaKids.'), 155))
@section('og_image', $product->images->first() ? asset('uploads/' . $product->images->first()->image_path) : asset('frontassets/images/logo.png'))
@section('og_url', route('product.show', $product->slug))
@section('canonical', route('product.show', $product->slug))


@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>{{ $product->name }}</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    @if($product->category)
                        <li class="breadcrumb-item">
                            <a href="#">{{ $product->category->name }}</a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="section-b-space">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <!-- Product Images -->
                    <div class="col-lg-6">
                        <div class="product-slick">
                            @forelse($product->images as $image)
                                <div>
                                    <img src="{{ asset('uploads/'.$image->image_path) }}"
                                         alt="{{ $product->name }}"
                                         class="img-fluid blur-up lazyload">
                                </div>
                            @empty
                                <div>
                                    <img src="{{ asset('frontassets/images/fashion-1/product/1.jpg') }}"
                                         alt="{{ $product->name }}"
                                         class="img-fluid">
                                </div>
                            @endforelse
                        </div>

                        @if($product->images->count() > 1)
                            <div class="row">
                                <div class="col-12 p-0">
                                    <div class="slider-nav">
                                        @foreach($product->images as $image)
                                            <div>
                                                <img src="{{ asset('uploads/'.$image->image_path) }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-fluid blur-up lazyload">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="col-lg-6 rtl-text">
                        <div class="product-right product-page-details">
                            <h2 class="main-title">{{ $product->name }}</h2>

                            <!-- Rating -->
                            @if($product->review_count > 0)
                                <div class="product-rating mb-2">
                                    <ul class="rating-list">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li>
                                                <i class="ri-star-{{ $i <= round($product->average_rating) ? 'fill' : 'line' }}"></i>
                                            </li>
                                        @endfor
                                    </ul>
                                    <div class="divider">|</div>
                                    <a href="#reviews">{{ $product->review_count }} Review(s)</a>
                                </div>
                            @endif

                            <!-- Price -->
                            <div class="price-text">
                                <h3 id="product-price">
                                    <span class="fw-normal">MRP:</span>
                                    @if($product->sale_price)
                                        ₹{{ number_format($product->sale_price, 2) }}
                                        <del>₹{{ number_format($product->price, 2) }}</del>
                                        @php
                                            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                        @endphp
                                        <span class="discounted-price">{{ $discount }}% Off</span>
                                    @else
                                        ₹{{ number_format($product->price, 2) }}
                                    @endif
                                </h3>
                                <span class="text">Inclusive of all taxes</span>
                            </div>

                            <!-- Stock Status -->
                            <div class="stock-status mb-3">
                                @php
                                    $totalStock = $sizeVariations->count() > 0
                                        ? $sizeVariations->sum('stock')
                                        : $product->stock;
                                @endphp
                                @if($totalStock > 10)
                                    <span class="badge bg-success">In Stock</span>
                                @elseif($totalStock > 0)
                                    <span class="badge bg-warning text-dark">Only {{ $totalStock }} left</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </div>

                            <!-- Size Selection -->
                            @if($sizeVariations->count() > 0)
                                <div class="size-box mb-4">
                                    <h6 class="product-title">Select Size</h6>
                                    <ul class="size-list" id="size-selector">
                                        @foreach($sizeVariations as $variation)
                                            <li class="{{ !$variation['is_available'] ? 'disabled' : '' }} {{ $firstAvailable && $firstAvailable['id'] == $variation['id'] ? 'active' : '' }}"
                                                data-variation-id="{{ $variation['id'] }}"
                                                data-size="{{ $variation['size'] }}"
                                                data-stock="{{ $variation['stock'] }}"
                                                data-price="{{ $variation['effective_price'] }}"
                                                data-available="{{ $variation['is_available'] ? '1' : '0' }}">
                                                <a href="javascript:void(0)">{{ $variation['size'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p class="size-error text-danger mt-2" id="size-error" style="display: none;">
                                        <i class="ri-error-warning-line"></i> Please select a size
                                    </p>
                                </div>
                            @endif

                            <!-- Hidden Inputs -->
                            <input type="hidden" id="product-id" value="{{ $product->id }}">
                            <input type="hidden" id="variation-id" value="{{ $firstAvailable['id'] ?? '' }}">
                            <input type="hidden" id="base-price" value="{{ $product->sale_price ?? $product->price }}">
                            <input type="hidden" id="has-variations" value="{{ $sizeVariations->count() > 0 ? '1' : '0' }}">

                            <!-- Quantity -->
                            <div class="qty-section mb-3">
                                <h6 class="product-title">Quantity</h6>
                                <div class="qty-box">
                                    <div class="input-group qty-container">
                                        <button class="btn quantity-left-minus" type="button" id="qty-minus">
                                            <i class="ri-subtract-line"></i>
                                        </button>
                                        <input type="number" name="quantity" id="quantity"
                                               class="form-control input-number" value="1" min="1"
                                               max="{{ $firstAvailable['stock'] ?? $product->stock ?? 99 }}" readonly>
                                        <button class="btn quantity-right-plus" type="button" id="qty-plus">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Add to Cart & Buy Now Buttons -->
                            <div class="product-buy-btn-group">
                                <button class="btn btn-animation btn-solid hover-solid scroll-button"
                                        id="add-to-cart-btn"
                                        data-product-id="{{ $product->id }}"
                                        {{ $totalStock <= 0 ? 'disabled' : '' }}>
                                    <i class="ri-shopping-cart-line me-1"></i>
                                    Add To Cart
                                </button>
                                <button class="btn btn-solid buy-button" id="buy-now-btn">
                                    Buy Now
                                </button>
                            </div>

                            <!-- Wishlist & Compare -->
                            <div class="buy-box compare-box mt-3">
                                <a href="#!">
                                    <i class="ri-heart-line"></i>
                                    <span>Add To Wishlist</span>
                                </a>
                                <a href="#!">
                                    <i class="ri-refresh-line"></i>
                                    <span>Add To Compare</span>
                                </a>
                            </div>

                            <!-- Product Details Accordion -->
                            <div class="border-product mt-4">
                                <h6 class="product-title">Product Details</h6>
                                <div class="product-description-content">{!! $product->description !!}</div>
                            </div>

                            @if($product->fabric_type)
                                <div class="border-product">
                                    <h6 class="product-title">Fabric</h6>
                                    <p>{{ $product->fabric_type }}</p>
                                </div>
                            @endif

                            <!-- Share -->
                            <div class="border-product">
                                <h6 class="product-title">Share It</h6>
                                <div class="product-icon">
                                    <ul class="product-social">
                                        <li><a href="#"><i class="ri-facebook-fill"></i></a></li>
                                        <li><a href="#"><i class="ri-twitter-fill"></i></a></li>
                                        <li><a href="#"><i class="ri-whatsapp-fill"></i></a></li>
                                        <li><a href="#"><i class="ri-instagram-fill"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <section class="section-b-space ratio_asos related-products">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="title1">
                            <h2 class="title-inner1">You May Also Like</h2>
                        </div>
                    </div>
                </div>
                <div class="row g-3 g-md-4 row-cols-2 row-cols-md-3 row-cols-xl-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div>
                            <x-product-card :product="$relatedProduct" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sizeSelector = document.getElementById('size-selector');
    const variationInput = document.getElementById('variation-id');
    const productId = document.getElementById('product-id').value;
    const priceElement = document.getElementById('product-price');
    const sizeError = document.getElementById('size-error');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const buyNowBtn = document.getElementById('buy-now-btn');
    const quantityInput = document.getElementById('quantity');
    const hasVariations = document.getElementById('has-variations').value === '1';
    const basePrice = parseFloat(document.getElementById('base-price').value);

    // Size button click handler
    // Size button click handler (Delegation)
    if (sizeSelector) {
        sizeSelector.addEventListener('click', function(e) {
            const li = e.target.closest('li');
            
            // Should be an LI, and not disabled
            if (!li || li.classList.contains('disabled')) return;

            // Prevent default behavior (especially for the anchor tag)
            e.preventDefault();

            // Remove active from all
            sizeSelector.querySelectorAll('li').forEach(function(item) {
                item.classList.remove('active');
            });

            // Add active to clicked
            li.classList.add('active');

            // Get data
            const variationId = li.dataset.variationId;
            const size = li.dataset.size;
            const stock = parseInt(li.dataset.stock);
            const price = parseFloat(li.dataset.price);

            // Update hidden input
            variationInput.value = variationId;

            // Hide error
            if (sizeError) sizeError.style.display = 'none';

            // Update price display
            updatePriceDisplay(price);

            // Update quantity max
            quantityInput.max = stock;
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock > 0 ? 1 : 0;
            }

            // Enable/disable add to cart
            addToCartBtn.disabled = stock <= 0;
            if(buyNowBtn) buyNowBtn.disabled = stock <= 0;
        });
    }

    // Price update function
    function updatePriceDisplay(newPrice) {
        let priceHtml = '<span class="fw-normal">MRP:</span> ₹' + newPrice.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        if (newPrice < basePrice) {
            const discount = Math.round(((basePrice - newPrice) / basePrice) * 100);
            priceHtml += ' <del>₹' + basePrice.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + '</del>';
            priceHtml += ' <span class="discounted-price">' + discount + '% Off</span>';
        }

        priceElement.innerHTML = priceHtml;
    }

    // Quantity buttons
    document.getElementById('qty-minus')?.addEventListener('click', function() {
        let qty = parseInt(quantityInput.value);
        if (qty > 1) quantityInput.value = qty - 1;
    });

    document.getElementById('qty-plus')?.addEventListener('click', function() {
        let qty = parseInt(quantityInput.value);
        let max = parseInt(quantityInput.max) || 99;
        if (qty < max) quantityInput.value = qty + 1;
    });

    // Add to cart button click
    addToCartBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        handleCartAction(false);
    });

    // Buy Now button click
    buyNowBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        handleCartAction(true);
    });

    function handleCartAction(isBuyNow) {
        // Validate size selection
        if (hasVariations && !variationInput.value) {
            if (sizeError) {
                sizeError.style.display = 'block';
                sizeSelector.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        const quantity = parseInt(quantityInput.value) || 1;
        const variationId = variationInput.value || null;

        // Call addToCartWithVariation function
        addToCartWithVariation(productId, quantity, variationId, isBuyNow);
    }
});

/**
 * Add to cart with variation support
 */
async function addToCartWithVariation(productId, quantity, variationId, isBuyNow = false) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const button = isBuyNow ? document.getElementById('buy-now-btn') : document.getElementById('add-to-cart-btn');
    if (!button) return; // Guard clause
    const originalContent = button.innerHTML;

    // Show loading
    button.disabled = true;
    button.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> ' + (isBuyNow ? 'Processing...' : 'Adding...');

    try {
        const requestBody = {
            product_id: parseInt(productId),
            quantity: parseInt(quantity),
            buy_now: isBuyNow,
            _token: csrfToken // Add token to body as fallback
        };

        if (variationId) {
            requestBody.variation_id = parseInt(variationId);
        }

        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        // Check if response is JSON
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new Error("Received non-JSON response from server");
        }

        const data = await response.json();

        if (data.success) {
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
                return; // Don't reset button, navigating away
            }
            
            // Show Success UI
            if (typeof showNotification === 'function') {
                showNotification(data.message || 'Added to cart!', 'success');
            } else {
                alert(data.message || 'Added to cart!');
            }
            
            if (typeof updateCartCount === 'function') {
                updateCartCount(data.cart_count);
            }

            // Refresh and open cart offcanvas
            if (typeof refreshCartOffcanvas === 'function') {
                await refreshCartOffcanvas();
                if (typeof openCartOffcanvas === 'function') {
                    openCartOffcanvas();
                }
            } else {
                console.warn('Cart offcanvas functions not defined');
            }

        } else {
            if (typeof showNotification === 'function') {
                showNotification(data.message || 'Failed to add to cart', 'error');
            } else {
                alert(data.message || 'Failed to add to cart');
            }
        }
    } catch (error) {
        console.error('Add to cart error:', error);
        if (typeof showNotification === 'function') {
            showNotification('Something went wrong. Please try again.', 'error');
        } else {
            alert('Something went wrong. Please try again.');
        }
    } finally {
        // Always reset button if we are not redirecting
        // Or if there was an error preventing redirect
        button.disabled = false;
        button.innerHTML = originalContent;
    }
}
</script>
@endpush
@push('styles')
<style>
/* Quill HTML description output */
.product-description-content { font-size: 14px; line-height: 1.8; color: #555; }
.product-description-content p { margin-bottom: 8px; }
.product-description-content strong, .product-description-content b { font-weight: 600; }
.product-description-content em { font-style: italic; }
.product-description-content h1, .product-description-content h2,
.product-description-content h3, .product-description-content h4 { font-weight: 600; margin: 8px 0 4px; color: #333; }
.product-description-content blockquote { border-left: 3px solid #ec8951; padding-left: 12px; color: #777; margin: 8px 0; }
.product-description-content a { color: #ec8951; text-decoration: underline; }

/* Strip default ol/ul browser/bootstrap styles */
.product-description-content ol,
.product-description-content ul {
    list-style: none;
    padding: 0;
    margin: 0 0 8px 0;
    /* Each <ol> resets its own counter — fixes continuous numbering */
    counter-reset: ql-list-counter;
}

/* Hide Quill's editor-only UI marker span */
.product-description-content .ql-ui { display: none; }

/* Force block — prevents inline rendering */
.product-description-content li[data-list] {
    display: block;
    list-style: none;
    padding-left: 1.8em;
    position: relative;
    margin-bottom: 4px;
}

/* Ordered: increment counter, render number before item */
.product-description-content li[data-list="ordered"] {
    counter-increment: ql-list-counter;
}
.product-description-content li[data-list="ordered"]::before {
    content: counter(ql-list-counter) ".";
    position: absolute;
    left: 0;
    font-weight: 500;
    color: #444;
    min-width: 1.4em;
    text-align: left;
}

/* Bullet: render dot before item */
.product-description-content li[data-list="bullet"]::before {
    content: "\2022";
    position: absolute;
    left: 0.35em;
    color: #ec8951;
    font-size: 1.1em;
    line-height: 1.7;
}

/* Indent levels */
.product-description-content .ql-indent-1 { padding-left: 3.6em; }
.product-description-content .ql-indent-2 { padding-left: 5.4em; }
.product-description-content .ql-indent-3 { padding-left: 7.2em; }
.product-description-content .ql-indent-4 { padding-left: 9.0em; }

/* Size selector styling */
.size-box .size-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.size-box .size-list li {
    border: 1px solid #ddd;
    padding: 8px 18px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.size-box .size-list li:hover:not(.disabled) {
    border-color: #333;
}

.size-box .size-list li.active {
    background: #333;
    border-color: #333;
}

.size-box .size-list li.active a {
    color: #fff;
}

.size-box .size-list li.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    text-decoration: line-through;
    background: #f5f5f5;
}

.size-box .size-list li a {
    color: #333;
    text-decoration: none;
    font-weight: 600;
}

/* Stock badge */
.stock-status .badge {
    font-size: 13px;
    padding: 6px 12px;
}

/* Quantity box fix */
.qty-section .qty-box {
    width: auto;
    display: inline-block;
}

.qty-section .qty-box .input-group {
    width: auto;
}

.qty-section .qty-box input {
    width: 60px;
    text-align: center;
}
</style>
@endpush

@push('json_ld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ Str::limit(strip_tags($product->description ?? ''), 300) }}",
    "image": "{{ $product->images->first() ? asset('uploads/' . $product->images->first()->image_path) : asset('frontassets/images/logo.png') }}",
    "sku": "{{ $product->slug }}",
    "brand": {
        "@@type": "Brand",
        "name": "JangaKids"
    },
    "offers": {
        "@@type": "Offer",
        "url": "{{ route('product.show', $product->slug) }}",
        "priceCurrency": "INR",
        "price": "{{ $product->sale_price ?? $product->price }}",
        "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
            "@@type": "Organization",
            "name": "JangaKids"
        }
    }@if($product->review_count > 0),
    "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ number_format($product->average_rating, 1) }}",
        "reviewCount": "{{ $product->review_count }}"
    }@endif
}
</script>
@endpush