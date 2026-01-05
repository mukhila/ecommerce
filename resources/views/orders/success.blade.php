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
            <div class="row">
                <div class="col-md-12">
                    <div class="success-text text-center">
                        <i class="ri-checkbox-circle-line" style="font-size: 80px; color: #28a745;"></i>
                        <h2>Thank You!</h2>
                        <p>Your order has been placed successfully.</p>
                        <p class="mb-0">Order Number: <strong>{{ $order->order_number }}</strong></p>
                        <p>Total Amount: <strong>₹{{ number_format($order->total, 2) }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6 offset-lg-3">
                    <div class="product-order">
                        <h3>Order Details</h3>
                        <table class="table table-borderless">
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }} × {{ $item->quantity }}</td>
                                        <td class="text-end">₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td><strong>Subtotal (Excl. GST)</strong></td>
                                    <td class="text-end"><strong>₹{{ number_format($order->subtotal, 2) }}</strong></td>
                                </tr>

                                @if($order->gst_breakdown)
                                    @foreach($order->gst_breakdown as $gstRate => $gstData)
                                        <tr>
                                            <td><small class="text-muted">GST @ {{ $gstData['rate'] }}%</small></td>
                                            <td class="text-end"><small class="text-muted">₹{{ number_format($gstData['gst_amount'], 2) }}</small></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>Total GST</strong></td>
                                        <td class="text-end"><strong>₹{{ number_format($order->gst_amount, 2) }}</strong></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td><strong>Tax (GST)</strong></td>
                                        <td class="text-end"><strong>₹{{ number_format($order->tax, 2) }}</strong></td>
                                    </tr>
                                @endif

                                <tr>
                                    <td><strong>Shipping</strong></td>
                                    <td class="text-end"><strong>
                                        @if($order->shipping_cost > 0)
                                            ₹{{ number_format($order->shipping_cost, 2) }}
                                        @else
                                            <span class="text-success">FREE</span>
                                        @endif
                                    </strong></td>
                                </tr>
                                <tr class="border-top">
                                    <td><h5 class="mb-0">Grand Total</h5></td>
                                    <td class="text-end"><h5 class="mb-0 text-success">₹{{ number_format($order->total, 2) }}</h5></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-solid">View My Orders</a>
                            <a href="{{ route('home') }}" class="btn btn-outline">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection
