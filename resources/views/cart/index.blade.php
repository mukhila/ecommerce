@extends('layouts.master')

@section('title', 'Shopping Cart')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Cart</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Cart</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="cart-section section-b-space">
        <div class="container">
            @if($cart->items->count() > 0)
                <div class="table-responsive">
                    <table class="table cart-table">
                        <thead>
                            <tr class="table-head">
                                <th>image</th>
                                <th>product name</th>
                                <th>price</th>
                                <th>quantity</th>
                                <th>total</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->items as $item)
                                <tr id="cart-item-{{ $item->id }}">
                                    <td>
                                        <a href="{{ route('product.show', $item->product->slug) }}">
                                            @if($item->product->images->count() > 0)
                                                <img src="{{ asset('uploads/' . $item->product->images->first()->image_path) }}"
                                                     class="img-fluid"
                                                     alt="{{ $item->product->name }}"
                                                     style="max-width: 100px;">
                                            @else
                                                <img src="{{ asset('frontassets/images/product-placeholder.jpg') }}"
                                                     class="img-fluid"
                                                     alt="{{ $item->product->name }}"
                                                     style="max-width: 100px;">
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('product.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                        @if($item->size_label)
                                            <div class="mt-1">
                                                <span class="badge bg-secondary">Size: {{ $item->size_label }}</span>
                                            </div>
                                        @elseif($item->attributes && isset($item->attributes['size']))
                                            <div class="mt-1">
                                                <span class="badge bg-secondary">Size: {{ $item->attributes['size']['label'] ?? $item->attributes['size'] }}</span>
                                            </div>
                                        @endif
                                        <div class="mobile-cart-content row">
                                            <div class="col">
                                                <div class="qty-box">
                                                    <div class="input-group qty-container">
                                                        <button class="btn quantity-left-minus" type="button">
                                                            <i class="ri-arrow-left-s-line"></i>
                                                        </button>
                                                        <input type="number"
                                                               readonly
                                                               name="qty"
                                                               class="form-control input-number"
                                                               value="{{ $item->quantity }}"
                                                               data-item-id="{{ $item->id }}"
                                                               min="1">
                                                        <button class="btn quantity-right-plus" type="button">
                                                            <i class="ri-arrow-right-s-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col table-price">
                                                <h2 class="td-color" id="item-subtotal-{{ $item->id }}">
                                                    ₹{{ number_format($item->subtotal, 2) }}
                                                </h2>
                                            </div>
                                            <div class="col">
                                                <h2 class="td-color">
                                                    <a href="javascript:void(0)"
                                                       class="icon remove-btn"
                                                       onclick="removeCartItem({{ $item->id }})">
                                                        <i class="ri-close-line"></i>
                                                    </a>
                                                </h2>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-price">
                                        <h2>₹{{ number_format($item->price, 2) }}</h2>
                                        @if($item->product->sale_price && $item->product->price > $item->product->sale_price)
                                            <h6 class="theme-color">
                                                You Save: ₹{{ number_format($item->product->price - $item->product->sale_price, 2) }}
                                            </h6>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="qty-box">
                                            <div class="input-group qty-container">
                                                <button class="btn quantity-left-minus" type="button">
                                                    <i class="ri-arrow-left-s-line"></i>
                                                </button>
                                                <input type="number"
                                                       readonly
                                                       name="qty"
                                                       class="form-control input-number"
                                                       value="{{ $item->quantity }}"
                                                       data-item-id="{{ $item->id }}"
                                                       min="1"
                                                       max="{{ $item->available_stock }}">
                                                <button class="btn quantity-right-plus" type="button">
                                                    <i class="ri-arrow-right-s-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h2 class="td-color" id="item-subtotal-{{ $item->id }}">
                                            ₹{{ number_format($item->subtotal, 2) }}
                                        </h2>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           class="icon remove-btn"
                                           onclick="removeCartItem({{ $item->id }})">
                                            <i class="ri-close-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="d-md-table-cell d-none text-end"><strong>Subtotal (Excl. GST):</strong></td>
                                <td class="d-md-none text-end"><strong>Subtotal:</strong></td>
                                <td>
                                    <h4>₹{{ number_format($cart->subtotal, 2) }}</h4>
                                </td>
                                <td></td>
                            </tr>
                            @if($cart->gst_breakdown)
                                @foreach($cart->gst_breakdown as $gstRate => $gstData)
                                    <tr class="gst-row">
                                        <td colspan="4" class="d-md-table-cell d-none text-end">
                                            <small class="text-muted">GST @ {{ $gstData['rate'] }}%:</small>
                                        </td>
                                        <td class="d-md-none text-end">
                                            <small class="text-muted">GST {{ $gstData['rate'] }}%:</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">₹{{ number_format($gstData['gst_amount'], 2) }}</small>
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr class="border-top">
                                <td colspan="4" class="d-md-table-cell d-none text-end"><strong>Total (Incl. GST):</strong></td>
                                <td class="d-md-none text-end"><strong>Total:</strong></td>
                                <td>
                                    <h2 id="cart-total" class="text-success">₹{{ number_format($cart->total, 2) }}</h2>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row cart-buttons">
                    <div class="col-6">
                        <a href="{{ route('home') }}" class="btn btn-solid text-capitalize">continue shopping</a>
                    </div>
                    <div class="col-6">
                        @auth
                            <a href="{{ route('checkout.index') }}" class="btn btn-solid text-capitalize">check out</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-solid text-capitalize">Login to Checkout</a>
                        @endauth
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12 empty-cart-cls text-center">
                            <img src="{{ asset('frontassets/images/icon-empty-cart.png') }}"
                                 class="img-fluid mb-4 mx-auto"
                                 alt="Empty Cart">
                            <h3><strong>Your Cart is Empty</strong></h3>
                            <h4>Add something to make me happy :)</h4>
                            <a href="{{ route('home') }}" class="btn btn-solid">continue shopping</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!--section end-->
@endsection
