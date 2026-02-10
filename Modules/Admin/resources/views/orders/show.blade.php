@extends('admin::layouts.main')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line"></i> Back to Orders
                    </a>
                </div>
                <h4 class="page-title">Order Details - {{ $order->order_number }}</h4>
            </div>
        </div>
    </div>

    <!-- Order Status Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Status</h5>
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payment Status</h5>
                    <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST">
                        @csrf
                        <select name="payment_status" class="form-select" onchange="this.form.submit()">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payment Method</h5>
                    <h3 class="mb-0">{{ strtoupper($order->payment_method) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Date</h5>
                    <h3 class="mb-0">{{ $order->created_at->format('d M Y') }}</h3>
                    <small class="text-muted">{{ $order->created_at->format('H:i A') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Shipment Tracking</h5>
                    <form action="{{ route('admin.orders.update-tracking', $order) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="courier_name" class="form-label">Courier Name</label>
                                <select class="form-select" id="courier_name" name="courier_name">
                                    <option value="">Select Courier</option>
                                    <option value="Blue Dart" {{ old('courier_name', $order->courier_name) == 'Blue Dart' ? 'selected' : '' }}>Blue Dart</option>
                                    <option value="Delhivery" {{ old('courier_name', $order->courier_name) == 'Delhivery' ? 'selected' : '' }}>Delhivery</option>
                                    <option value="DTDC" {{ old('courier_name', $order->courier_name) == 'DTDC' ? 'selected' : '' }}>DTDC</option>
                                    <option value="FedEx" {{ old('courier_name', $order->courier_name) == 'FedEx' ? 'selected' : '' }}>FedEx</option>
                                    <option value="India Post" {{ old('courier_name', $order->courier_name) == 'India Post' ? 'selected' : '' }}>India Post</option>
                                    <option value="Professional Couriers" {{ old('courier_name', $order->courier_name) == 'Professional Couriers' ? 'selected' : '' }}>Professional Couriers</option>
                                    <option value="Other" {{ old('courier_name', $order->courier_name) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="tracking_number" class="form-label">Tracking Number / AWB</label>
                                <input type="text" class="form-control" id="tracking_number" name="tracking_number"
                                       value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Enter tracking number">
                            </div>
                            <div class="col-md-3">
                                <label for="estimated_delivery_date" class="form-label">Est. Delivery Date</label>
                                <input type="date" class="form-control" id="estimated_delivery_date" name="estimated_delivery_date"
                                       value="{{ old('estimated_delivery_date', $order->estimated_delivery_date) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-save-line"></i> Update Tracking
                                </button>
                            </div>
                        </div>
                    </form>
                    @if($order->tracking_number)
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="ri-checkbox-circle-line"></i>
                            <strong>Tracking Active:</strong> {{ $order->courier_name }} - {{ $order->tracking_number }}
                            @if($order->estimated_delivery_date)
                                | Expected by: {{ \Carbon\Carbon::parse($order->estimated_delivery_date)->format('d M Y') }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Customer Information</h4>
                </div>
                <div class="card-body">
                    @if($order->user)
                        <p class="mb-2"><strong>Name:</strong> {{ $order->user->name }}</p>
                        <p class="mb-2"><strong>Email:</strong> {{ $order->user->email }}</p>
                        <p class="mb-2"><strong>Phone:</strong> {{ $order->phone ?? 'N/A' }}</p>
                        @if($order->alternate_phone)
                            <p class="mb-2"><strong>Alternate Phone:</strong> {{ $order->alternate_phone }}</p>
                        @endif
                    @else
                        <p class="mb-2"><strong>Guest Name:</strong> {{ $order->guest_name }}</p>
                        <p class="mb-2"><strong>Guest Email:</strong> {{ $order->guest_email }}</p>
                        <p class="mb-2"><strong>Phone:</strong> {{ $order->phone ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">Shipping Address</h4>
                </div>
                <div class="card-body">
                    @if($order->shippingAddress)
                        <p class="mb-1">{{ $order->shippingAddress->full_name }}</p>
                        <p class="mb-1">{{ $order->shippingAddress->address_line1 }}</p>
                        @if($order->shippingAddress->address_line2)
                            <p class="mb-1">{{ $order->shippingAddress->address_line2 }}</p>
                        @endif
                        <p class="mb-1">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}</p>
                        <p class="mb-1">{{ $order->shippingAddress->postal_code }}</p>
                        <p class="mb-1">{{ $order->shippingAddress->country }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $order->shippingAddress->phone }}</p>
                        @if($order->shippingAddress->alternate_phone)
                            <p class="mb-0"><strong>Alt Phone:</strong> {{ $order->shippingAddress->alternate_phone }}</p>
                        @endif
                    @else
                        <p class="text-muted">No shipping address available</p>
                    @endif
                </div>
            </div>

            @if($order->notes)
                <!-- Order Notes -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Order Notes</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Items -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Order Items</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
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
                                                         class="me-2"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product_name }}</strong>
                                                    @if($item->attributes && is_array($item->attributes))
                                                        <br>
                                                        <small class="text-muted">
                                                            @foreach($item->attributes as $attrName => $attrData)
                                                                {{ ucfirst($attrName) }}: {{ is_array($attrData) ? ($attrData['label'] ?? $attrData['value'] ?? implode(', ', $attrData)) : $attrData }}{{ !$loop->last ? ', ' : '' }}
                                                            @endforeach
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->product_sku ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong>₹{{ number_format($item->total, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Summary -->
                    <div class="row mt-4">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tax (GST 18%):</strong></td>
                                        <td class="text-end">₹{{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Shipping Cost:</strong></td>
                                        <td class="text-end">
                                            @if($order->shipping_cost > 0)
                                                ₹{{ number_format($order->shipping_cost, 2) }}
                                            @else
                                                <span class="badge bg-success">FREE</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($order->discount > 0)
                                        <tr>
                                            <td><strong>Discount:</strong></td>
                                            <td class="text-end text-success">-₹{{ number_format($order->discount, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="border-top">
                                        <td><h4 class="mb-0">Total:</h4></td>
                                        <td class="text-end"><h4 class="mb-0">₹{{ number_format($order->total, 2) }}</h4></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
