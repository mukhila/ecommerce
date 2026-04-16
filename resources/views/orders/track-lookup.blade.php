@extends('layouts.master')

@section('title', 'Track Your Order - JangaKids')

@push('styles')
<style>
.track-lookup-card { max-width: 560px; margin: 0 auto; }
.track-result-card { border-left: 4px solid var(--theme-color, #ff4c3b); }

.status-badge { font-size: 13px; padding: 5px 14px; border-radius: 20px; font-weight: 600; }
.status-pending    { background: #fff3cd; color: #856404; }
.status-processing { background: #cce5ff; color: #004085; }
.status-shipped    { background: #d4edda; color: #155724; }
.status-delivered  { background: #d4edda; color: #155724; }
.status-cancelled  { background: #f8d7da; color: #721c24; }

.track-steps { display:flex; justify-content:space-between; position:relative; margin: 24px 0 8px; }
.track-steps::before {
    content:''; position:absolute; top:20px; left:0; right:0; height:3px;
    background:#e9ecef; z-index:0;
}
.track-step { flex:1; text-align:center; position:relative; z-index:1; }
.step-dot {
    width:40px; height:40px; border-radius:50%; background:#e9ecef;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 8px; font-size:17px; color:#aaa; transition:all .3s;
}
.track-step.done .step-dot  { background:#28a745; color:#fff; }
.track-step.active .step-dot { background: var(--theme-color, #ff4c3b); color:#fff; }
.step-label { font-size:12px; color:#666; font-weight:500; }
.track-step.done .step-label,
.track-step.active .step-label { color:#333; font-weight:600; }
</style>
@endpush

@section('content')

<div class="breadcrumb-section">
    <div class="container">
        <h2>Track Order</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Track Order</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-b-space">
    <div class="container">

        {{-- ── Lookup Form ── --}}
        <div class="track-lookup-card">
            <div class="theme-card mb-4">
                <h4 class="mb-1">Track Your Order</h4>
                <p class="text-muted small mb-4">Enter your order number and the email or phone used while ordering.</p>

                <form method="POST" action="{{ route('order.track.lookup') }}">
                    @csrf
                    <div class="form-box mb-3">
                        <label class="form-label fw-600">Order Number <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('order_number') is-invalid @enderror"
                               name="order_number" value="{{ old('order_number') }}"
                               placeholder="e.g. ORD-6831ABCD12" required>
                        @error('order_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-box mb-4">
                        <label class="form-label fw-600">Email or Mobile Number <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('identity') is-invalid @enderror"
                               name="identity" value="{{ old('identity') }}"
                               placeholder="Email address or 10-digit mobile number" required>
                        @error('identity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">We use this to verify the order belongs to you.</small>
                    </div>

                    <button type="submit" class="btn btn-solid w-100">Track Order</button>
                </form>
            </div>

            {{-- ── Tracking Result ── --}}
            @if(isset($order))
                <div class="card track-result-card shadow-sm">
                    <div class="card-body">

                        {{-- Order Header --}}
                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1">{{ $order->order_number }}</h5>
                                <small class="text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        @if($order->status !== 'cancelled')
                            {{-- Progress Steps --}}
                            <div class="track-steps">
                                @php
                                    $steps = [
                                        'pending'    => ['icon' => 'ri-shopping-cart-line',   'label' => 'Order Placed', 'order' => 1],
                                        'processing' => ['icon' => 'ri-settings-3-line',       'label' => 'Processing',   'order' => 2],
                                        'shipped'    => ['icon' => 'ri-truck-line',            'label' => 'Shipped',      'order' => 3],
                                        'delivered'  => ['icon' => 'ri-checkbox-circle-line',  'label' => 'Delivered',    'order' => 4],
                                    ];
                                    $currentOrder = $steps[$order->status]['order'] ?? 1;
                                @endphp
                                @foreach($steps as $key => $step)
                                    @php
                                        $isDone   = $step['order'] < $currentOrder;
                                        $isActive = $step['order'] === $currentOrder;
                                    @endphp
                                    <div class="track-step {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                                        <div class="step-dot"><i class="{{ $step['icon'] }}"></i></div>
                                        <div class="step-label">{{ $step['label'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-danger text-center py-2">
                                <i class="ri-close-circle-line me-1"></i>
                                This order has been cancelled.
                                @if($order->cancellation_reason)
                                    <br><small>Reason: {{ $order->cancellation_reason }}</small>
                                @endif
                            </div>
                        @endif

                        <hr>

                        {{-- Shipping / Tracking Info --}}
                        @if($order->tracking_number)
                            <div class="row mb-3">
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted d-block">Courier</small>
                                    <strong>{{ $order->courier_name }}</strong>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <small class="text-muted d-block">Tracking No.</small>
                                    <strong>{{ $order->tracking_number }}</strong>
                                </div>
                                @if($order->estimated_delivery_date)
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">Est. Delivery</small>
                                        <strong>{{ \Carbon\Carbon::parse($order->estimated_delivery_date)->format('d M Y') }}</strong>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Order total --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Order Total</span>
                            <strong class="text-success">₹{{ number_format($order->total, 2) }}</strong>
                        </div>

                        @auth
                            <div class="mt-3">
                                <a href="{{ route('order.tracking', $order) }}" class="btn btn-solid btn-sm">
                                    View Full Details
                                </a>
                            </div>
                        @endauth

                    </div>
                </div>
            @endif

            @if(isset($notFound) && $notFound)
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-1"></i>
                    No order found with that order number and email/phone combination. Please check and try again.
                </div>
            @endif

        </div>{{-- end track-lookup-card --}}

    </div>
</section>

@endsection
