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

            {{-- Guest login prompt --}}
            @guest
            <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                <i class="ri-information-line me-2 fs-5"></i>
                <span>
                    Already have an account?
                    <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.index')) }}" class="fw-bold">Log in</a>
                    for faster checkout and to track your orders.
                </span>
            </div>
            @endguest

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
                                       value="{{ old('full_name', $user->name ?? '') }}"
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
                                       value="{{ old('email', $user->email ?? '') }}"
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
                                       placeholder="10-digit mobile number"
                                       maxlength="10"
                                       value="{{ old('phone') }}"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback" id="phone-client-error"></div>
                                @enderror
                                <small class="text-muted">Enter 10-digit Indian mobile number (e.g. 9876543210)</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="alternate_phone" class="form-label">Alternate Phone</label>
                                <input type="text"
                                       class="form-control @error('alternate_phone') is-invalid @enderror"
                                       id="alternate_phone"
                                       name="alternate_phone"
                                       placeholder="10-digit mobile number (optional)"
                                       maxlength="10"
                                       value="{{ old('alternate_phone') }}">
                                @error('alternate_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback" id="alt-phone-client-error"></div>
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
                                                        @foreach($item->attributes as $attrData)
                                                            @if(is_array($attrData) && isset($attrData['value']))
                                                                {{ $attrData['value'] }}
                                                            @elseif(is_string($attrData) || is_numeric($attrData))
                                                                {{ $attrData }}
                                                            @else
                                                                {{ json_encode($attrData) }}
                                                            @endif
                                                            {{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                    </small>
                                                @endif
                                            </div>
                                            <span>₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                {{-- Coupon Input --}}
                                <div class="coupon-box mt-3 mb-2">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="couponCodeInput" class="form-control"
                                               placeholder="Coupon code" maxlength="50"
                                               style="text-transform:uppercase;">
                                        <button class="btn btn-solid btn-sm" type="button" id="applyCouponBtn">Apply</button>
                                    </div>
                                    <div id="couponMessage" class="small mt-1"></div>
                                </div>
                                <input type="hidden" name="coupon_code" id="appliedCouponCode" value="">

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

                                    <li id="couponDiscountLine" style="display:none; color:#28a745;">
                                        Coupon (<span id="couponCodeLabel"></span>)
                                        <span class="count text-success" id="couponDiscountAmt"></span>
                                    </li>
                                </ul>
                                <ul class="total">
                                    <li>Grand Total <span class="count" id="grandTotalDisplay">₹{{ number_format($cart->total + $shippingCost, 2) }}</span></li>
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
                                                    <label for="payment-razorpay">Razorpay (Card / UPI / Netbanking)</label>
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
(function () {
    const cartTotal   = {{ $cart->total }};
    const shippingCost = {{ $shippingCost }};
    let   appliedDiscount = 0;

    // ── Coupon ────────────────────────────────────────────────────────────────
    document.getElementById('applyCouponBtn').addEventListener('click', function () {
        const code = document.getElementById('couponCodeInput').value.trim().toUpperCase();
        const msg  = document.getElementById('couponMessage');

        if (!code) {
            msg.innerHTML = '<span class="text-danger">Please enter a coupon code.</span>';
            return;
        }

        this.disabled = true;
        this.textContent = '…';

        const emailVal = (document.getElementById('email').value || '').trim();
        const phoneVal = (document.getElementById('phone').value || '').trim();

        fetch('{{ route('coupon.apply') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                code,
                cart_total: cartTotal,
                email: emailVal || null,
                phone: phoneVal || null,
            }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                appliedDiscount = data.discount;
                document.getElementById('appliedCouponCode').value = data.code;
                document.getElementById('couponCodeLabel').textContent = data.code;
                document.getElementById('couponDiscountAmt').textContent = '−₹' + data.discount.toFixed(2);
                document.getElementById('couponDiscountLine').style.display = '';
                updateGrandTotal();
                msg.innerHTML = '<span class="text-success"><i class="ri-checkbox-circle-line"></i> ' + data.message + '</span>';
            } else {
                msg.innerHTML = '<span class="text-danger"><i class="ri-error-warning-line"></i> ' + data.message + '</span>';
            }
        })
        .catch(() => {
            msg.innerHTML = '<span class="text-danger">Something went wrong. Try again.</span>';
        })
        .finally(() => {
            this.disabled = false;
            this.textContent = 'Apply';
        });
    });

    function updateGrandTotal() {
        const grand = Math.max(0, cartTotal + shippingCost - appliedDiscount);
        document.getElementById('grandTotalDisplay').textContent = '₹' + grand.toFixed(2);
    }

    // Allow applying coupon with Enter key
    document.getElementById('couponCodeInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('applyCouponBtn').click();
        }
    });

    // ── Form validation ───────────────────────────────────────────────────────
    const phoneRegex    = /^[6-9]\d{9}$/;
    const postalRegex   = /^\d{6}$/;

    function setFieldError(inputId, errorId, message) {
        const input = document.getElementById(inputId);
        const err   = document.getElementById(errorId);
        if (!input) return;
        if (message) {
            input.classList.add('is-invalid');
            if (err) { err.textContent = message; err.style.display = 'block'; }
        } else {
            input.classList.remove('is-invalid');
            if (err) { err.textContent = ''; err.style.display = ''; }
        }
    }

    // Live phone validation
    document.getElementById('phone').addEventListener('input', function () {
        const val = this.value.trim();
        if (val && !phoneRegex.test(val)) {
            setFieldError('phone', 'phone-client-error', 'Enter a valid 10-digit mobile number starting with 6–9.');
        } else {
            setFieldError('phone', 'phone-client-error', null);
        }
    });

    // Live alternate phone validation
    const altPhoneEl = document.getElementById('alternate_phone');
    if (altPhoneEl) {
        altPhoneEl.addEventListener('input', function () {
            const val = this.value.trim();
            if (val && !phoneRegex.test(val)) {
                setFieldError('alternate_phone', 'alt-phone-client-error', 'Enter a valid 10-digit mobile number starting with 6–9.');
            } else {
                setFieldError('alternate_phone', 'alt-phone-client-error', null);
            }
        });
    }

    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        let hasError = false;

        const phone      = document.getElementById('phone').value.trim();
        const altPhone   = document.getElementById('alternate_phone')?.value.trim();
        const postalCode = document.getElementById('postal_code').value.trim();
        const country    = document.getElementById('country').value;

        if (!phoneRegex.test(phone)) {
            setFieldError('phone', 'phone-client-error', 'Enter a valid 10-digit Indian mobile number starting with 6–9.');
            document.getElementById('phone').scrollIntoView({ behavior: 'smooth', block: 'center' });
            hasError = true;
        }

        if (altPhone && !phoneRegex.test(altPhone)) {
            setFieldError('alternate_phone', 'alt-phone-client-error', 'Enter a valid 10-digit Indian mobile number starting with 6–9.');
            if (!hasError) document.getElementById('alternate_phone').scrollIntoView({ behavior: 'smooth', block: 'center' });
            hasError = true;
        }

        if (country === 'India' && !postalRegex.test(postalCode)) {
            document.getElementById('postal_code').classList.add('is-invalid');
            if (!hasError) document.getElementById('postal_code').scrollIntoView({ behavior: 'smooth', block: 'center' });
            hasError = true;
        }

        if (hasError) e.preventDefault();
    });
})();
</script>
@endpush
