@if($cartCount > 0)
    @php
        $freeShippingThreshold = 3000;
        $remaining = $freeShippingThreshold - $cartTotal;
        $progressPercentage = min(($cartTotal / $freeShippingThreshold) * 100, 100);
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
            @foreach($cartItems as $item)
                <li id="offcanvas-item-{{ $item->id }}">
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
                                            <strong>{{ $attrName }}:</strong> {{ is_array($attrData) ? $attrData['value'] : $attrData }}
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
                                        onclick="removeCartItemOffcanvas({{ $item->id }})"
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
                    <h5>Sub Total : <span id="offcanvas-cart-total">₹{{ number_format($cartTotal, 2) }}</span></h5>
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
