@extends('layouts.master')

@section('title', 'Complete Payment')

@section('content')
<div class="breadcrumb-section">
    <div class="container">
        <h2>Complete Payment</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Payment</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-b-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="card p-4">
                    <h4 class="mb-3">Order #{{ $order->order_number }}</h4>
                    <p class="mb-1">Amount: <strong>₹{{ number_format($order->total, 2) }}</strong></p>
                    <p class="text-muted mb-4">You will be redirected to Razorpay's secure payment page.</p>
                    <button id="rzp-button" class="btn btn-solid w-100">Pay Now</button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-2 w-100">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        key: "{{ $razorpayData['key_id'] }}",
        amount: "{{ $razorpayData['amount'] }}",
        currency: "{{ $razorpayData['currency'] }}",
        name: "{{ config('app.name') }}",
        description: "Order #{{ $razorpayData['order_number'] }}",
        order_id: "{{ $razorpayData['razorpay_order_id'] }}",
        prefill: {
            name: "{{ $razorpayData['customer_name'] }}",
            email: "{{ $razorpayData['customer_email'] }}",
            contact: "{{ $razorpayData['customer_phone'] }}"
        },
        handler: function(response) {
            // Submit payment details to the callback route
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('payment.callback') }}';

            var fields = {
                '_token': '{{ csrf_token() }}',
                'razorpay_payment_id': response.razorpay_payment_id,
                'razorpay_order_id': response.razorpay_order_id,
                'razorpay_signature': response.razorpay_signature
            };

            Object.keys(fields).forEach(function(key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        },
        modal: {
            ondismiss: function() {
                window.location.href = '{{ route('payment.failed') }}';
            }
        },
        theme: { color: "#ff4c3b" }
    };

    var rzp = new Razorpay(options);

    document.getElementById('rzp-button').addEventListener('click', function(e) {
        rzp.open();
        e.preventDefault();
    });

    // Auto-open on page load
    window.onload = function() { rzp.open(); };
</script>
@endpush
