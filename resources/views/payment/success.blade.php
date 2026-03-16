@extends('layouts.master')

@section('title', 'Payment Successful')

@section('content')
<div class="breadcrumb-section">
    <div class="container">
        <h2>Payment Successful</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Payment Success</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-b-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="success-text text-center mb-5">
                    <i class="ri-checkbox-circle-line text-success" style="font-size: 80px;"></i>
                    <h2 class="mt-2">Payment Successful!</h2>
                    <p class="lead">Your order has been confirmed.</p>

                    <div class="card mt-4 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-1">Order Number: <strong class="text-primary">{{ $order->order_number }}</strong></h5>
                            <p class="mb-0 text-muted">
                                A confirmation email will be sent to
                                {{ $order->shippingAddress->email ?? $order->user->email ?? '' }}
                            </p>
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
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr class="border-bottom">
                                        <td>
                                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                                            @if($item->size_label)
                                                <small class="text-muted">Size: {{ $item->size_label }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold">₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end">Subtotal</td>
                                        <td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-end">GST</td>
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
                                        <td colspan="2" class="text-end"><strong>Grand Total</strong></td>
                                        <td class="text-end"><strong class="text-primary">₹{{ number_format($order->total, 2) }}</strong></td>
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
@endsection
