@extends('layouts.master')

@section('title', 'Complete Payment')

@section('content')
<!-- breadcrumb start -->
<div class="breadcrumb-section">
    <div class="container">
        <h2>Complete Payment</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item"><a href="{{ route('checkout.index') }}">Checkout</a></li>
                <li class="breadcrumb-item active">Payment</li>
            </ol>
        </nav>
    </div>
</div>
<!-- breadcrumb end -->

<!--section start-->
<section class="section-b-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="ri-secure-payment-line" style="font-size: 80px; color: #3498db;"></i>
                        </div>

                        <h3 class="mb-3">Complete Your Payment</h3>
                        <p class="text-muted mb-4">
                            Order Number: <strong>{{ $order->order_number }}</strong>
                        </p>

                        <div class="payment-amount mb-4">
                            <h4 class="text-success">₹{{ number_format($order->total, 2) }}</h4>
                        </div>

                        <div class="alert alert-info">
                            <i class="ri-information-line"></i>
                            Click the button below to proceed with secure payment through Razorpay
                        </div>

                        <button id="razorpay-button" class="btn btn-solid btn-lg w-100 mb-3">
                            <i class="ri-secure-payment-fill"></i> Pay Now
                        </button>

                        <a href="{{ route('payment.failed', ['order_id' => $order->id]) }}" class="btn btn-outline btn-sm">
                            Cancel Payment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--section end-->
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('razorpay-button');

        const options = {
            "key": "{{ $razorpayOrder['key_id'] }}",
            "amount": "{{ $razorpayOrder['amount'] }}",
            "currency": "{{ $razorpayOrder['currency'] }}",
            "name": "{{ config('app.name') }}",
            "description": "Order #{{ $razorpayOrder['order_number'] }}",
            "order_id": "{{ $razorpayOrder['razorpay_order_id'] }}",
            "handler": function (response){
                // Payment successful - send details to server
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('payment.callback') }}';

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Add Razorpay response data
                const fields = {
                    'razorpay_payment_id': response.razorpay_payment_id,
                    'razorpay_order_id': response.razorpay_order_id,
                    'razorpay_signature': response.razorpay_signature
                };

                for (const [key, value] of Object.entries(fields)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
            },
            "prefill": {
                "name": "{{ $razorpayOrder['customer_name'] }}",
                "email": "{{ $razorpayOrder['customer_email'] }}",
                "contact": "{{ $razorpayOrder['customer_phone'] ?? '' }}"
            },
            "theme": {
                "color": "#3498db"
            },
            "modal": {
                "ondismiss": function(){
                    if (confirm('Are you sure you want to cancel this payment?')) {
                        window.location.href = '{{ route('payment.failed', ['order_id' => $order->id]) }}';
                    }
                }
            }
        };

        const rzp = new Razorpay(options);

        rzp.on('payment.failed', function (response){
            alert('Payment failed: ' + response.error.description);
            window.location.href = '{{ route('payment.failed', ['order_id' => $order->id]) }}';
        });

        button.onclick = function(e){
            e.preventDefault();
            rzp.open();
        };

        // Auto-open Razorpay checkout after 1 second
        setTimeout(function() {
            rzp.open();
        }, 1000);
    });
</script>
@endpush
