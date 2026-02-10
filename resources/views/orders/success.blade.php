@extends('layouts.master')

@section('title', 'Order Success')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Order Placed Successfully</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Order Success</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!--section start-->
    <section class="section-b-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="success-text text-center mb-5">
                        <i class="ri-checkbox-circle-line text-success" style="font-size: 80px;"></i>
                        <h2 class="mt-2">Thank You!</h2>
                        <p class="lead">Your order has been placed successfully.</p>
                        <div class="card mt-4 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-0">Order Number: <strong class="text-primary">{{ $order->id }}</strong></h5>
                                <p class="mb-0 text-muted">A confirmation email has been sent to {{ $order->shippingAddress->email ?? Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="product-order card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0">Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead>
                                        <tr class="border-bottom">
                                            <th>Product</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr class="border-bottom">
                                                <td>
                                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                    <small class="text-muted d-block mb-1">Quantity: {{ $item->quantity }}</small>
                                                    @if($item->attributes)
                                                        <small class="text-muted">
                                                            @foreach($item->attributes as $attrData)
                                                                @if(is_array($attrData) && isset($attrData['value']))
                                                                    {{ $attrData['value'] }}
                                                                @elseif(is_string($attrData) || is_numeric($attrData))
                                                                    {{ $attrData }}
                                                                @endif
                                                                {{ !$loop->last ? ', ' : '' }}
                                                            @endforeach
                                                        </small>
                                                    @endif
                                                </td>
                                                <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                                                <td class="text-end fw-bold">₹{{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-end">Subtotal (Excl. GST)</td>
                                            <td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end">Tax (GST)</td>
                                            <td class="text-end">₹{{ number_format($order->gst_amount ?? $order->tax, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end">Shipping</td>
                                            <td class="text-end">
                                                @if($order->shipping_cost > 0)
                                                    ₹{{ number_format($order->shipping_cost, 2) }}
                                                @else
                                                    <span class="text-success">FREE</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="border-top">
                                            <td colspan="2" class="text-end"><h5 class="mb-0">Grand Total</h5></td>
                                            <td class="text-end"><h5 class="mb-0 text-primary">₹{{ number_format($order->total, 2) }}</h5></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-solid me-2">View My Orders</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection
