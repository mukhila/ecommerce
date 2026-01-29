@props(['product'])

<div class="basic-product theme-product-1">
    <div class="overflow-hidden">
        <div class="img-wrapper">
            @if($product->is_featured)
                <div class="ribbon"><span>Featured</span></div>
            @endif

            <a href="{{ route('product.show', $product->slug) }}">
                @if($product->images->where('is_primary', true)->first())
                    <img src="{{ Storage::disk('public_uploads')->url($product->images->where('is_primary', true)->first()->image_path) }}"
                        class="img-fluid blur-up lazyload" alt="{{ $product->name }}">
                @else
                    <img src="{{ asset('frontassets/images/fashion-1/product/1.jpg') }}"
                        class="img-fluid blur-up lazyload" alt="{{ $product->name }}">
                @endif
            </a>

            <div class="cart-info">
                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                    <i class="ri-heart-line"></i>
                </a>
                <button onclick="addToCart({{ $product->id }}, 1)" title="Add to cart" data-product-id="{{ $product->id }}">
                    <i class="ri-shopping-cart-line"></i>
                </button>
                <a href="{{ route('product.show', $product->slug) }}" title="Quick View">
                    <i class="ri-eye-line"></i>
                </a>
                <a href="#!" title="Compare">
                    <i class="ri-loop-left-line"></i>
                </a>
            </div>
        </div>

        <div class="product-detail">
            <div>
                <div class="brand-w-color">
                    <a class="product-title" href="{{ route('product.show', $product->slug) }}">
                        {{ $product->name }}
                    </a>
                </div>
                <h6>{{ $product->category->name ?? '' }}</h6>

                {{-- Rating Display --}}
                @if($product->review_count > 0)
                    <div class="rating-label mb-2">
                        <div class="d-flex align-items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="ri-star-{{ $i <= round($product->average_rating) ? 'fill' : 'line' }}"
                                   style="color: #ffc107; font-size: 14px;"></i>
                            @endfor
                            <span class="ms-2 text-muted" style="font-size: 13px;">
                                {{ number_format($product->average_rating, 1) }} ({{ $product->review_count }})
                            </span>
                        </div>
                    </div>
                @endif

                <h4 class="price">
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
                </h4>
            </div>
        </div>
    </div>
</div>
