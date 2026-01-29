@extends('layouts.master')

@section('title', $product->name)

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
                                    <img src="{{ Storage::disk('public_uploads')->url($image->image_path) }}"
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
                                                <img src="{{ Storage::disk('public_uploads')->url($image->image_path) }}"
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
                                <a href="{{ route('checkout.index') }}" class="btn btn-solid buy-button">
                                    Buy Now
                                </a>
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
                                <p>{{ $product->description }}</p>
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
    const quantityInput = document.getElementById('quantity');
    const hasVariations = document.getElementById('has-variations').value === '1';
    const basePrice = parseFloat(document.getElementById('base-price').value);

    // Size button click handler
    if (sizeSelector) {
        sizeSelector.querySelectorAll('li:not(.disabled)').forEach(function(li) {
            li.addEventListener('click', function() {
                // Remove active from all
                sizeSelector.querySelectorAll('li').forEach(function(item) {
                    item.classList.remove('active');
                });

                // Add active to clicked
                this.classList.add('active');

                // Get data
                const variationId = this.dataset.variationId;
                const size = this.dataset.size;
                const stock = parseInt(this.dataset.stock);
                const price = parseFloat(this.dataset.price);

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
            });
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
        addToCartWithVariation(productId, quantity, variationId);
    });
});

/**
 * Add to cart with variation support
 */
async function addToCartWithVariation(productId, quantity, variationId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const button = document.getElementById('add-to-cart-btn');
    const originalContent = button.innerHTML;

    // Show loading
    button.disabled = true;
    button.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Adding...';

    try {
        const requestBody = {
            product_id: parseInt(productId),
            quantity: parseInt(quantity)
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

        const data = await response.json();

        if (data.success) {
            showNotification(data.message || 'Added to cart!', 'success');
            updateCartCount(data.cart_count);

            // Refresh and open cart offcanvas
            if (typeof refreshCartOffcanvas === 'function') {
                refreshCartOffcanvas().then(() => {
                    if (typeof openCartOffcanvas === 'function') {
                        openCartOffcanvas();
                    }
                });
            }
        } else {
            showNotification(data.message || 'Failed to add to cart', 'error');
        }
    } catch (error) {
        console.error('Add to cart error:', error);
        showNotification('Something went wrong. Please try again.', 'error');
    } finally {
        button.disabled = false;
        button.innerHTML = originalContent;
    }
}
</script>

<style>
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
