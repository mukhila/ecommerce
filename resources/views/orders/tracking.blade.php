@extends('layouts.master')

@section('title', 'Track Order - ' . $order->order_number)

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Track Your Order</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">My Account</a></li>
                    <li class="breadcrumb-item active">Track Order</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!--section start-->
    <section class="section-b-space">
        <div class="container">
            <!-- Order Header -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h6 class="mb-1 text-muted">Order Number</h6>
                                    <h4 class="mb-0">{{ $order->order_number }}</h4>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="mb-1 text-muted">Order Date</h6>
                                    <p class="mb-0">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="mb-1 text-muted">Order Total</h6>
                                    <h5 class="mb-0 text-success">₹{{ number_format($order->total, 2) }}</h5>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="mb-1 text-muted">Payment</h6>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->status === 'cancelled')
                <!-- Cancelled Order -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            <i class="ri-close-circle-line" style="font-size: 60px;"></i>
                            <h4 class="mt-3">This order has been cancelled</h4>
                            <p class="mb-0">If you have any questions, please contact our support team.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Order Status Timeline -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Order Status</h4>

                                <div class="order-track">
                                    <div class="order-track-steps">
                                        @foreach($statusSteps as $status => $step)
                                            <div class="order-track-step {{ $step['order'] <= $currentStep ? 'completed' : '' }}">
                                                <div class="order-track-status">
                                                    <span class="order-track-status-dot"></span>
                                                    <span class="order-track-status-line"></span>
                                                </div>
                                                <div class="order-track-text">
                                                    <i class="{{ $step['icon'] }}" style="font-size: 24px;"></i>
                                                    <p class="order-track-text-stat">{{ $step['label'] }}</p>
                                                    @if($order->status === $status)
                                                        <span class="badge bg-primary">Current</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tracking Information -->
                @if($order->tracking_number)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <i class="ri-truck-line"></i> Shipment Tracking
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6 class="text-muted mb-1">Courier Service</h6>
                                            <p class="mb-0"><strong>{{ $order->courier_name }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="text-muted mb-1">Tracking Number</h6>
                                            <p class="mb-0"><strong>{{ $order->tracking_number }}</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="text-muted mb-1">Estimated Delivery</h6>
                                            <p class="mb-0">
                                                @if($order->estimated_delivery_date)
                                                    <strong>{{ \Carbon\Carbon::parse($order->estimated_delivery_date)->format('d M Y') }}</strong>
                                                @else
                                                    <span class="text-muted">Not available</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Order Details -->
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Order Items</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th class="text-end">Price</th>
                                            @if($order->status === 'delivered')
                                                <th class="text-center">Review</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product && $item->product->images->count() > 0)
                                                            <img src="{{ asset('uploads/' . $item->product->images->first()->image_path) }}"
                                                                 alt="{{ $item->product_name }}"
                                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                                 class="me-2">
                                                        @endif
                                                        <span>{{ $item->product_name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $item->quantity }}</td>
                                                <td class="text-end">₹{{ number_format($item->total, 2) }}</td>
                                                @if($order->status === 'delivered')
                                                    <td class="text-center">
                                                        @auth
                                                            @php
                                                                $hasReviewed = \App\Models\ProductReview::where('product_id', $item->product_id)
                                                                    ->where('user_id', Auth::id())
                                                                    ->where('order_id', $order->id)
                                                                    ->exists();
                                                            @endphp

                                                            @if($hasReviewed)
                                                                <span class="badge bg-success">
                                                                    <i class="ri-checkbox-circle-line"></i> Reviewed
                                                                </span>
                                                            @else
                                                                <a href="{{ route('review.create', ['product_id' => $item->product_id, 'order_id' => $order->id]) }}"
                                                                   class="btn btn-sm btn-primary">
                                                                    <i class="ri-edit-line"></i> Write Review
                                                                </a>
                                                            @endif
                                                        @endauth
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Delivery Address</h5>
                            @if($order->shippingAddress)
                                <p class="mb-1"><strong>{{ $order->shippingAddress->full_name }}</strong></p>
                                <p class="mb-1">{{ $order->shippingAddress->address_line1 }}</p>
                                @if($order->shippingAddress->address_line2)
                                    <p class="mb-1">{{ $order->shippingAddress->address_line2 }}</p>
                                @endif
                                <p class="mb-1">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}</p>
                                <p class="mb-1">{{ $order->shippingAddress->postal_code }}</p>
                                <p class="mb-1">{{ $order->shippingAddress->country }}</p>
                                <p class="mb-0"><i class="ri-phone-line"></i> {{ $order->shippingAddress->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal (Excl. GST)</span>
                                <span>₹{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>GST</span>
                                <span>₹{{ number_format($order->gst_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span>
                                    @if($order->shipping_cost > 0)
                                        ₹{{ number_format($order->shipping_cost, 2) }}
                                    @else
                                        <span class="text-success">FREE</span>
                                    @endif
                                </span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total</strong>
                                <strong class="text-success">₹{{ number_format($order->total, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-solid">Back to My Orders</a>
                    <a href="{{ route('home') }}" class="btn btn-outline">Continue Shopping</a>
                </div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection

@push('styles')
<style>
.order-track {
    position: relative;
}

.order-track-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.order-track-step {
    flex: 1;
    position: relative;
    text-align: center;
}

.order-track-status {
    position: relative;
    padding-bottom: 20px;
}

.order-track-status-dot {
    display: block;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #ddd;
    margin: 0 auto 10px;
    position: relative;
    z-index: 2;
    transition: all 0.3s;
}

.order-track-step.completed .order-track-status-dot {
    background: #28a745;
}

.order-track-status-line {
    position: absolute;
    top: 15px;
    left: 50%;
    width: 100%;
    height: 3px;
    background: #ddd;
    z-index: 1;
}

.order-track-step:last-child .order-track-status-line {
    display: none;
}

.order-track-step.completed .order-track-status-line {
    background: #28a745;
}

.order-track-text {
    margin-top: 10px;
}

.order-track-text-stat {
    font-size: 14px;
    font-weight: 500;
    margin-top: 5px;
    margin-bottom: 5px;
}

.order-track-step.completed .order-track-text i {
    color: #28a745;
}

@media (max-width: 768px) {
    .order-track-steps {
        flex-direction: column;
    }

    .order-track-status-line {
        width: 3px;
        height: 50px;
        left: 15px;
        top: 30px;
    }
}
</style>
@endpush
