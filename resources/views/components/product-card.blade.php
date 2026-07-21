@props(['product'])

@php
    $primaryImage = $product->images->where('is_primary', true)->first() 
        ?? $product->images->first();
    $imagePath = $primaryImage 
        ? asset('uploads/' . $primaryImage->image_path) 
        : asset('frontassets/images/fashion-1/product/1.jpg');

    // Assign a pastel gradient class based on ID for visual variety in case image is missing or as a fallback
    $gradients = ['pb1', 'pb2', 'pb3', 'pb4', 'pb5', 'pb6', 'pb7', 'pb8'];
    $gradientClass = $gradients[$product->id % count($gradients)];
@endphp

<div class="pcard" onclick="window.location='{{ route('product.show', $product->slug) }}'">
    <div class="pcard-img {{ $gradientClass }}">
        <img src="{{ $imagePath }}" class="img-fluid blur-up lazyload" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
        
        @if($product->sale_price)
            @php
                $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
            @endphp
            <span class="pcard-badge badge-sale">-{{ $discount }}%</span>
        @elseif($product->is_featured)
            <span class="pcard-badge badge-hot">Hot</span>
        @else
            <span class="pcard-badge badge-new">New</span>
        @endif

        <div class="pcard-actions" onclick="event.stopPropagation();">
            <button class="act-btn wishlist-icon" data-product-id="{{ $product->id }}" title="Add to Wishlist">
                @php
                    $isInWishlist = false;
                    if(auth()->check()) {
                        $isInWishlist = \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                    }
                @endphp
                <i class="{{ $isInWishlist ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
            </button>
            <button class="act-btn" onclick="addToCart({{ $product->id }}, 1)" title="Add to Cart" data-product-id="{{ $product->id }}">
                <i class="ri-shopping-cart-line"></i>
            </button>
        </div>
    </div>
    <div class="pcard-info">
        <div class="pcard-cat">{{ $product->category->name ?? 'Kids Wear' }}</div>
        <div class="pcard-name">{{ $product->name }}</div>
        <div class="pcard-foot">
            <div class="pcard-price">
                @if($product->sale_price)
                    ₹{{ number_format($product->sale_price, 0) }}
                    <del>₹{{ number_format($product->price, 0) }}</del>
                @else
                    ₹{{ number_format($product->price, 0) }}
                @endif
            </div>
            @if($product->review_count > 0)
                <div class="pcard-stars">
                    @for ($i = 1; $i <= 5; $i++)
                        @if($i <= round($product->average_rating))
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                    {{ number_format($product->average_rating, 1) }}
                </div>
            @else
                <div class="pcard-stars">★★★★★ 5.0</div>
            @endif
        </div>
    </div>
</div>
