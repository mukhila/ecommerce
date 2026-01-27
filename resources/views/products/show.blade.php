@extends('layouts.master')

@section('title', $product->name)

@push('styles')
<style>
    /* Size Button Styles */
    .size-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 15px 0;
    }

    .size-btn {
        min-width: 50px;
        height: 44px;
        padding: 0 16px;
        border: 2px solid #dee2e6;
        background: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .size-btn:hover:not(.out-of-stock):not(.selected) {
        border-color: #333;
    }

    .size-btn.selected {
        border-color: #333;
        background: #333;
        color: #fff;
    }

    .size-btn.out-of-stock {
        opacity: 0.5;
        cursor: not-allowed;
        text-decoration: line-through;
        background: #f5f5f5;
    }

    .size-btn.out-of-stock::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #999;
    }

    /* Price Animation */
    .product-price {
        transition: opacity 0.15s ease-out, transform 0.15s ease-out;
    }

    .product-price.price-changing {
        opacity: 0;
        transform: translateY(-5px);
    }

    .product-price.price-updated {
        animation: priceSlideIn 0.3s ease-out;
    }

    @keyframes priceSlideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stock Badge */
    .stock-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
    }

    .stock-badge.in-stock {
        background: #d4edda;
        color: #155724;
    }

    .stock-badge.low-stock {
        background: #fff3cd;
        color: #856404;
    }

    .stock-badge.out-of-stock {
        background: #f8d7da;
        color: #721c24;
    }

    /* Size Selection Error */
    .size-error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 8px;
        display: none;
    }

    .size-error.show {
        display: block;
        animation: shake 0.3s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>
@endpush

@section('content')
<section class="section-b-space">
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div class="product-slick">
                    @forelse($product->images as $image)
                        <div>
                            <img src="{{ Storage::url($image->image_path) }}"
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
                                    <img src="{{ Storage::url($image->image_path) }}"
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
                <div class="product-right">
                    <h2>{{ $product->name }}</h2>

                    <!-- Rating -->
                    @if($product->review_count > 0)
                        <div class="rating-section mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="ri-star-{{ $i <= round($product->average_rating) ? 'fill' : 'line' }}"
                                   style="color: #ffc107;"></i>
                            @endfor
                            <span class="ms-2">({{ $product->review_count }} reviews)</span>
                        </div>
                    @endif

                    <!-- Price Display -->
                    <div class="product-price-wrapper mb-3">
                        <h3 class="product-price" id="product-price">
                            @if($firstAvailable && $firstAvailable['price'])
                                ₹{{ number_format($firstAvailable['effective_price'], 2) }}
                                @if($product->sale_price && $firstAvailable['price'] != $product->price)
                                    <del class="text-muted ms-2" style="font-size: 0.7em;">
                                        ₹{{ number_format($product->price, 2) }}
                                    </del>
                                @endif
                            @elseif($product->sale_price)
                                ₹{{ number_format($product->sale_price, 2) }}
                                <del class="text-muted ms-2" style="font-size: 0.7em;">
                                    ₹{{ number_format($product->price, 2) }}
                                </del>
                            @else
                                ₹{{ number_format($product->price, 2) }}
                            @endif
                        </h3>
                        <small class="text-muted">(Inclusive of all taxes)</small>
                    </div>

                    <!-- Stock Status -->
                    <div class="stock-status mb-3" id="stock-status">
                        @if($firstAvailable)
                            @if($firstAvailable['stock'] > 10)
                                <span class="stock-badge in-stock">In Stock</span>
                            @elseif($firstAvailable['stock'] > 0)
                                <span class="stock-badge low-stock">Only {{ $firstAvailable['stock'] }} left</span>
                            @endif
                        @elseif(!$product->hasSizeVariations() && $product->stock > 0)
                            <span class="stock-badge in-stock">In Stock</span>
                        @else
                            <span class="stock-badge out-of-stock">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Size Selection -->
                    @if($sizeVariations->count() > 0)
                        <div class="size-selection mb-4">
                            <h6 class="product-title mb-2">
                                Select Size: <span id="selected-size-label" class="fw-bold">{{ $firstAvailable['size'] ?? '' }}</span>
                            </h6>

                            <div class="size-selector" id="size-selector">
                                @foreach($sizeVariations as $variation)
                                    <button type="button"
                                            class="size-btn {{ !$variation['is_available'] ? 'out-of-stock' : '' }} {{ $firstAvailable && $firstAvailable['id'] == $variation['id'] ? 'selected' : '' }}"
                                            data-variation-id="{{ $variation['id'] }}"
                                            data-size="{{ $variation['size'] }}"
                                            data-stock="{{ $variation['stock'] }}"
                                            data-price="{{ $variation['effective_price'] }}"
                                            data-available="{{ $variation['is_available'] ? '1' : '0' }}"
                                            {{ !$variation['is_available'] ? 'disabled' : '' }}>
                                        {{ $variation['size'] }}
                                    </button>
                                @endforeach
                            </div>

                            <div class="size-error" id="size-error">Please select a size</div>
                        </div>
                    @endif

                    <!-- Hidden Inputs -->
                    <input type="hidden" id="product-id" value="{{ $product->id }}">
                    <input type="hidden" id="variation-id" value="{{ $firstAvailable['id'] ?? '' }}">
                    <input type="hidden" id="base-price" value="{{ $product->sale_price ?? $product->price }}">
                    <input type="hidden" id="has-variations" value="{{ $sizeVariations->count() > 0 ? '1' : '0' }}">

                    <!-- Quantity & Add to Cart -->
                    <div class="product-buttons mt-4">
                        <div class="qty-box mb-3">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <button type="button" class="btn quantity-left-minus" data-type="minus">
                                        <i class="ri-subtract-line"></i>
                                    </button>
                                </span>
                                <input type="number" name="quantity" id="quantity" class="form-control input-number"
                                       value="1" min="1" max="{{ $firstAvailable['stock'] ?? $product->stock ?? 99 }}">
                                <span class="input-group-append">
                                    <button type="button" class="btn quantity-right-plus" data-type="plus">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <button id="add-to-cart-btn" class="btn btn-solid btn-lg w-100"
                                {{ (!$firstAvailable && $sizeVariations->count() > 0) || (!$product->isInStock()) ? 'disabled' : '' }}>
                            <i class="ri-shopping-cart-line me-2"></i>
                            Add to Cart
                        </button>
                    </div>

                    <!-- Product Details Accordion -->
                    <div class="border-product mt-4">
                        <h6 class="product-title">Product Details</h6>
                        <div class="product-description">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    @if($product->fabric_type)
                        <div class="border-product">
                            <h6 class="product-title">Fabric</h6>
                            <p>{{ $product->fabric_type }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
@if($product->approvedReviews->count() > 0)
    @include('reviews.partials.reviews-section', ['reviews' => $product->approvedReviews, 'product' => $product])
@endif

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="section-b-space ratio_asos">
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
    const stockStatus = document.getElementById('stock-status');
    const sizeLabel = document.getElementById('selected-size-label');
    const sizeError = document.getElementById('size-error');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const quantityInput = document.getElementById('quantity');
    const hasVariations = document.getElementById('has-variations').value === '1';
    const basePrice = parseFloat(document.getElementById('base-price').value);

    // Size button click handler
    if (sizeSelector) {
        sizeSelector.addEventListener('click', function(e) {
            const btn = e.target.closest('.size-btn');
            if (!btn || btn.disabled) return;

            // Remove selected from all
            sizeSelector.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));

            // Add selected to clicked
            btn.classList.add('selected');

            // Update hidden input
            const variationId = btn.dataset.variationId;
            const size = btn.dataset.size;
            const stock = parseInt(btn.dataset.stock);
            const price = parseFloat(btn.dataset.price);

            variationInput.value = variationId;

            // Update size label
            if (sizeLabel) sizeLabel.textContent = size;

            // Hide error
            if (sizeError) sizeError.classList.remove('show');

            // Update price with animation
            updatePrice(price);

            // Update stock status
            updateStockStatus(stock);

            // Update quantity max
            quantityInput.max = stock;
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock;
            }

            // Enable add to cart
            addToCartBtn.disabled = stock <= 0;
        });
    }

    // Price update with animation
    function updatePrice(newPrice) {
        priceElement.classList.add('price-changing');

        setTimeout(() => {
            priceElement.innerHTML = '₹' + newPrice.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Show original price if different
            if (newPrice < basePrice) {
                priceElement.innerHTML += '<del class="text-muted ms-2" style="font-size: 0.7em;">₹' +
                    basePrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) +
                    '</del>';
            }

            priceElement.classList.remove('price-changing');
            priceElement.classList.add('price-updated');

            setTimeout(() => priceElement.classList.remove('price-updated'), 300);
        }, 150);
    }

    // Stock status update
    function updateStockStatus(stock) {
        let html = '';
        if (stock > 10) {
            html = '<span class="stock-badge in-stock">In Stock</span>';
        } else if (stock > 0) {
            html = '<span class="stock-badge low-stock">Only ' + stock + ' left</span>';
        } else {
            html = '<span class="stock-badge out-of-stock">Out of Stock</span>';
        }
        stockStatus.innerHTML = html;
    }

    // Quantity buttons
    document.querySelector('.quantity-left-minus')?.addEventListener('click', function() {
        let qty = parseInt(quantityInput.value);
        if (qty > 1) quantityInput.value = qty - 1;
    });

    document.querySelector('.quantity-right-plus')?.addEventListener('click', function() {
        let qty = parseInt(quantityInput.value);
        let max = parseInt(quantityInput.max) || 99;
        if (qty < max) quantityInput.value = qty + 1;
    });

    // Add to cart
    addToCartBtn?.addEventListener('click', function() {
        // Validate size selection
        if (hasVariations && !variationInput.value) {
            if (sizeError) {
                sizeError.classList.add('show');
                sizeSelector.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        const quantity = parseInt(quantityInput.value) || 1;

        // Disable button during request
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-2"></i>Adding...';

        // Build request data
        const data = {
            product_id: productId,
            quantity: quantity
        };

        if (variationInput.value) {
            data.variation_id = variationInput.value;
        }

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count in header
                if (typeof updateCartCount === 'function') {
                    updateCartCount(data.cart_count);
                }

                // Show success message
                showToast('success', data.message || 'Added to cart!');

                // Optionally open cart offcanvas
                if (typeof openCartOffcanvas === 'function') {
                    openCartOffcanvas();
                }
            } else {
                showToast('error', data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Something went wrong. Please try again.');
        })
        .finally(() => {
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="ri-shopping-cart-line me-2"></i>Add to Cart';
        });
    });

    // Toast notification helper
    function showToast(type, message) {
        // Use your existing toast implementation or create a simple one
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: 'top',
                position: 'right',
                backgroundColor: type === 'success' ? '#28a745' : '#dc3545'
            }).showToast();
        } else {
            alert(message);
        }
    }
});
</script>
@endpush
