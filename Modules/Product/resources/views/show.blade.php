@extends('layouts.master')

@section('title', $product->name)

@section('content')

<!-- breadcrumb start -->
<div class="breadcrumb-section">
    <div class="container">
        <h2>{{ $product->name }}</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
                @if($product->category)
                    <li class="breadcrumb-item">{{ $product->category->name }}</li>
                @endif
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- breadcrumb End -->

<!-- section start -->
<section>
    <div class="collection-wrapper">
        <div class="container">
            <div class="collection-wrapper">
                <div class="row g-4">
                    {{-- Product Images --}}
                    <div class="col-lg-4">
                        <div class="product-slick">
                            @forelse($product->images as $image)
                                <div><img src="{{ asset('uploads/' . $image->image_path) }}" alt="{{ $product->name }}"
                                        class="w-100 img-fluid blur-up lazyload"></div>
                            @empty
                                <div><img src="{{ asset('frontassets/images/fashion-1/product/1.jpg') }}" alt="{{ $product->name }}"
                                        class="w-100 img-fluid blur-up lazyload"></div>
                            @endforelse
                        </div>
                        @if($product->images->count() > 1)
                            <div class="row">
                                <div class="col-12">
                                    <div class="slider-nav">
                                        @foreach($product->images as $image)
                                            <div><img src="{{ asset('uploads/' . $image->image_path) }}" alt="{{ $product->name }}"
                                                    class="img-fluid blur-up lazyload"></div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Product Details --}}
                    <div class="col-lg-4">
                        <div class="product-page-details product-description-box sticky-details mt-0">
                            @if($product->is_featured)
                                <div class="trending-text ">
                                    <img src="{{ asset('frontassets/images/product-details/trending.gif') }}" class="img-fluid" alt="">
                                    <h5>Featured Product!</h5>
                                </div>
                            @endif

                            <h2 class="main-title">{{ $product->name }}</h2>

                            @if($product->category)
                                <div class="product-category">
                                    <span class="text-muted">Category:</span>
                                    <a href="#">{{ $product->category->name }}</a>
                                </div>
                            @endif

                            <div class="price-text">
                                <h3>
                                    <span class="fw-normal">MRP:</span>
                                    @if($product->sale_price)
                                        ₹{{ number_format($product->sale_price, 2) }}
                                        <del class="text-muted">₹{{ number_format($product->price, 2) }}</del>
                                    @else
                                        ₹{{ number_format($product->price, 2) }}
                                    @endif
                                </h3>
                                <span>Inclusive of all taxes</span>
                            </div>

                            <div class="size-delivery-info flex-wrap">
                                <a href="#!" class=""><i class="ri-truck-line"></i>
                                    Free Delivery & Return </a>

                                <a href="#!" class=""><i class="ri-questionnaire-line"></i>
                                    Ask a Question </a>
                            </div>

                            <div class="accordion accordion-flush product-accordion" id="accordionFlushExample">
                                {{-- Product Description --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                            aria-expanded="false" aria-controls="flush-collapseOne">
                                            Product Description
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <p>{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Information --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseTwo" aria-expanded="true"
                                            aria-controls="flush-collapseTwo">
                                            Information
                                        </button>
                                    </h2>
                                    <div id="flush-collapseTwo" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <div class="bordered-box border-0 mt-0 pt-0">
                                                <h4 class="sub-title">Product Info</h4>
                                                <ul class="shipping-info">
                                                    <li><span>SKU: </span>{{ $product->slug }}</li>
                                                    <li><span>Stock Status: </span>{{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</li>
                                                    <li><span>Quantity: </span>{{ $product->stock }} Items Left</li>
                                                    @if($product->category)
                                                        <li><span>Category: </span>{{ $product->category->name }}</li>
                                                    @endif
                                                </ul>
                                            </div>

                                            @if($product->attributes->count() > 0)
                                                <div class="bordered-box">
                                                    <h4 class="sub-title">Product Attributes</h4>
                                                    <ul class="shipping-info">
                                                        @foreach($product->attributes->groupBy('attribute_id') as $attributeGroup)
                                                            @php
                                                                $firstAttr = $attributeGroup->first();
                                                            @endphp
                                                            <li>
                                                                <span>{{ $firstAttr->attribute->name ?? 'Attribute' }}: </span>
                                                                @foreach($attributeGroup as $attr)
                                                                    {{ $attr->attributeValue->value ?? 'N/A' }}{{ !$loop->last ? ', ' : '' }}
                                                                @endforeach
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <div class="bordered-box">
                                                <h4 class="sub-title">Delivery Details</h4>
                                                <ul class="delivery-details">
                                                    <li><i class="ri-truck-line"></i> Your order is likely to reach you within 7 days.</li>
                                                    <li><i class="ri-arrow-left-right-line"></i> Hassle free returns within 7 Days.</li>
                                                </ul>
                                            </div>

                                            <div class="dashed-border-box mb-0">
                                                <h4 class="sub-title">Guaranteed Safe Checkout</h4>
                                                <img class="img-fluid payment-img" alt=""
                                                    src="{{ asset('frontassets/images/product-details/payments.png') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Form --}}
                    <div class="col-lg-4">
                        <div class="product-page-details product-form-box product-right-box d-flex align-items-center flex-column my-0">

                            {{-- Dynamic Attribute Selection --}}
                            @php
                                $groupedAttributes = $product->attributes->groupBy('attribute_id');
                            @endphp

                            @foreach($groupedAttributes as $attributeId => $attributeGroup)
                                @php
                                    $firstAttr = $attributeGroup->first();
                                    $attributeName = $firstAttr->attribute->name ?? 'Attribute';
                                    $attributeSlug = $firstAttr->attribute->slug ?? 'attr';
                                @endphp

                                <h4 class="sub-title {{ $loop->first ? '' : 'mt-3' }}">{{ $attributeName }}:</h4>
                                <div class="variation-box size-box">
                                    <ul class="select-size">
                                    @foreach($attributeGroup as $attr)
                                            <li>
                                                <a href="javascript:void(0)"
                                                   class="attribute-option"
                                                   data-attribute-id="{{ $attributeId }}"
                                                   data-value-id="{{ $attr->attributeValue->id ?? '' }}"
                                                   data-attribute-name="{{ $attributeName }}"
                                                   data-value="{{ $attr->attributeValue->value ?? 'N/A' }}"
                                                   data-stock="{{ $attr->stock ?? '' }}"
                                                   data-price="{{ $attr->price ?? '' }}">
                                                    {{ $attr->attributeValue->value ?? 'N/A' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach

                            {{-- Selected Attributes Display (Hidden) --}}
                            <input type="hidden" id="selectedAttributes" value="">

                            {{-- Quantity --}}
                            <div class="product-buttons">
                                <h4 class="sub-title mt-3">Quantity:</h4>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <button type="button" class="btn quantity-left-minus" data-type="minus">
                                                <i class="ri-subtract-line"></i>
                                            </button>
                                        </span>
                                        <input type="text" name="quantity" class="form-control input-number" value="1">
                                        <span class="input-group-prepend">
                                            <button type="button" class="btn quantity-right-plus" data-type="plus">
                                                <i class="ri-add-line"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                {{-- Add to Cart Button --}}
                                <div class="product-buttons mt-3 w-100">
                                    <button id="cartEffect"
                                            class="btn btn-solid hover-solid btn-animation w-100"
                                            data-action="add-to-cart"
                                            data-product-id="{{ $product->id }}"
                                            onclick="event.preventDefault(); addToCartWithAttributes({{ $product->id }})">
                                        <i class="ri-shopping-cart-line"></i>
                                        <span>Add To Cart</span>
                                    </button>
                                </div>

                                {{-- Wishlist Button --}}
                                <div class="product-buttons mt-2 w-100">
                                    <button class="btn btn-outline hover-solid btn-animation w-100">
                                        <i class="ri-heart-line"></i>
                                        <span>Add To Wishlist</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- section end -->

{{-- Reviews Section --}}
<section class="section-b-space pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="product-section-box">
                    <ul class="nav nav-tabs custom-nav" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#reviews-tab"
                                    type="button" role="tab">
                                Reviews ({{ $product->review_count }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#description-tab"
                                    type="button" role="tab">
                                Description
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content custom-tab">
                        {{-- Reviews Tab --}}
                        <div class="tab-pane fade show active" id="reviews-tab" role="tabpanel">
                            @include('reviews.partials.reviews-section', ['product' => $product])
                        </div>

                        {{-- Description Tab --}}
                        <div class="tab-pane fade" id="description-tab" role="tabpanel">
                            <div class="description-box p-3">
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Related Products --}}
@if($relatedProducts->count() > 0)
<section class="section-b-space pt-0 ratio_asos">
    <div class="container">
        <div class="title1 section-t-space">
            <h4>Related Products</h4>
            <h2 class="title-inner1">You May Also Like</h2>
        </div>
        <div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
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
        const attributeOptions = document.querySelectorAll('.attribute-option');
        const selectedAttributesInput = document.getElementById('selectedAttributes');

        // Handle attribute selection
        attributeOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();

                // Get the attribute ID to find siblings
                const attributeId = this.dataset.attributeId;

                // Remove active class from siblings with same attribute ID
                attributeOptions.forEach(opt => {
                    if (opt.dataset.attributeId === attributeId) {
                        opt.classList.remove('active');
                    }
                });

                // Add active class to clicked option
                this.classList.add('active');

                // Update selected attributes
                updateSelectedAttributes();
            });
        });

        function updateSelectedAttributes() {
            const selectedAttrs = {};

            // Get all active options
            document.querySelectorAll('.attribute-option.active').forEach(activeOption => {
                const attrName = activeOption.dataset.attributeName;
                const attrValue = activeOption.dataset.value;
                const valueId = activeOption.dataset.valueId;

                selectedAttrs[attrName] = {
                    value: attrValue,
                    valueId: valueId
                };
            });

            // Store in hidden input as JSON
            selectedAttributesInput.value = JSON.stringify(selectedAttrs);
        }
    });

    // Function to add to cart with attributes
    function addToCartWithAttributes(productId) {
        const quantityInput = document.querySelector('input[name=quantity]');
        const quantity = parseInt(quantityInput.value) || 1;
        const selectedAttributesInput = document.getElementById('selectedAttributes');
        const selectedAttributes = selectedAttributesInput.value;

        // Count total attribute groups required
        const totalAttributeGroups = document.querySelectorAll('.variation-box').length;
        
        // Parse the attributes
        let attributes = {};
        try {
            attributes = JSON.parse(selectedAttributes || '{}');
        } catch (e) {
            console.error('Error parsing attributes:', e);
        }

        // Check if all attributes are selected
        const selectedCount = Object.keys(attributes).length;
        if (selectedCount < totalAttributeGroups) {
            alert('Please select all required options (Size/Color/etc) before adding to cart.');
            return;
        }

        // Display selected attributes for user confirmation
        let attributesText = '';
        for (const [key, value] of Object.entries(attributes)) {
            attributesText += `${key}: ${value.value}\n`;
        }

        console.log('Adding to cart:', {
            productId: productId,
            quantity: quantity,
            attributes: attributes
        });

        // Call the existing addToCart function with attributes
        addToCart(productId, quantity, attributes);
    }
</script>
@endpush
