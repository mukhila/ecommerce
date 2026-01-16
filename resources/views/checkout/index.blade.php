@extends('layouts.master')

@section('title', 'Checkout')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Checkout</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!--section start-->
    <section class="section-b-space checkout-section-2">
        <div class="container">
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="checkout-title">
                            <h3>Shipping Address</h3>
                        </div>
                        <div class="row check-out">
                            <div class="form-group col-md-6">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('full_name') is-invalid @enderror"
                                       id="full_name"
                                       name="full_name"
                                       value="{{ old('full_name', $user->name) }}"
                                       required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="alternate_phone" class="form-label">Alternate Phone</label>
                                <input type="text"
                                       class="form-control @error('alternate_phone') is-invalid @enderror"
                                       id="alternate_phone"
                                       name="alternate_phone"
                                       value="{{ old('alternate_phone') }}">
                                @error('alternate_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="address_line1" class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('address_line1') is-invalid @enderror"
                                       id="address_line1"
                                       name="address_line1"
                                       placeholder="Street address, P.O. box, company name, etc."
                                       value="{{ old('address_line1') }}"
                                       required>
                                @error('address_line1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="address_line2" class="form-label">Address Line 2</label>
                                <input type="text"
                                       class="form-control @error('address_line2') is-invalid @enderror"
                                       id="address_line2"
                                       name="address_line2"
                                       placeholder="Apartment, suite, unit, building, floor, etc."
                                       value="{{ old('address_line2') }}">
                                @error('address_line2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('city') is-invalid @enderror"
                                       id="city"
                                       name="city"
                                       value="{{ old('city') }}"
                                       required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('state') is-invalid @enderror"
                                       id="state"
                                       name="state"
                                       value="{{ old('state') }}"
                                       required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('postal_code') is-invalid @enderror"
                                       id="postal_code"
                                       name="postal_code"
                                       value="{{ old('postal_code') }}"
                                       required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                <select class="form-control @error('country') is-invalid @enderror"
                                        id="country"
                                        name="country"
                                        required>
                                    <option value="India" {{ old('country', 'India') == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                                    <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>UK</option>
                                    <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                </select>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="notes" class="form-label">Order Notes (Optional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes"
                                          name="notes"
                                          rows="3"
                                          placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="checkout-details">
                            <div class="order-box">
                                <div class="title-box">
                                    <div>Product <span>Total</span></div>
                                </div>
                                <ul class="qty">
                                    @foreach($cart->items as $item)
                                        <li>
                                            <div>
                                                {{ $item->product->name }} × {{ $item->quantity }}
                                                @if($item->attributes)
                                                    <br>
                                                    <small class="text-muted">
                                                        @foreach($item->attributes as $attrName => $attrData)
                                                            {{ $attrName }}: {{ is_array($attrData) ? $attrData['value'] : $attrData }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                    </small>
                                                @endif
                                            </div>
                                            <span>₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <ul class="sub-total">
                                    <li>Subtotal (Excl. GST) <span class="count">₹{{ number_format($cart->subtotal, 2) }}</span></li>

                                    @if($cart->gst_breakdown)
                                        @foreach($cart->gst_breakdown as $gstRate => $gstData)
                                            <li class="gst-line">
                                                <small>GST @ {{ $gstData['rate'] }}%</small>
                                                <span class="count"><small>₹{{ number_format($gstData['gst_amount'], 2) }}</small></span>
                                            </li>
                                        @endforeach
                                    @endif

                                    <li class="border-top pt-2">Cart Total (Incl. GST) <span class="count">₹{{ number_format($cart->total, 2) }}</span></li>

                                    @php
                                        $shippingCost = $cart->total >= 3000 ? 0 : 100;
                                    @endphp
                                    <li>Shipping
                                        <span class="count">
                                            @if($shippingCost == 0)
                                                <span class="text-success">FREE</span>
                                            @else
                                                ₹{{ number_format($shippingCost, 2) }}
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                                <ul class="total">
                                    <li>Grand Total <span class="count">₹{{ number_format($cart->total + $shippingCost, 2) }}</span></li>
                                </ul>
                            </div>

                            <div class="payment-box">
                                <div class="upper-box">
                                    <div class="payment-options">
                                        <ul>
                                            <li>
                                                <div class="radio-option">
                                                    <input type="radio" name="payment_method" id="payment-cod" value="cod" checked>
                                                    <label for="payment-cod">Cash on Delivery</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="radio-option">
                                                    <input type="radio" name="payment_method" id="payment-razorpay" value="razorpay">
                                                    <label for="payment-razorpay">Razorpay (Card/UPI/Netbanking)</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-solid btn-block w-100">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!--section end-->
@endsection

@push('scripts')
<script>
    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const phone = document.getElementById('phone').value;
        const postalCode = document.getElementById('postal_code').value;

        // Basic phone validation
        if (phone && !/^\d{10}$/.test(phone.replace(/\D/g, ''))) {
            e.preventDefault();
            alert('Please enter a valid 10-digit phone number');
            return false;
        }

        // Postal code validation for India
        const country = document.getElementById('country').value;
        if (country === 'India' && postalCode && !/^\d{6}$/.test(postalCode)) {
            e.preventDefault();
            alert('Please enter a valid 6-digit postal code');
            return false;
        }
    });
</script>
@endpush
