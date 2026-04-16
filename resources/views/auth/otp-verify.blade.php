@extends('layouts.master')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Verify OTP</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Verify OTP</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <h3>Enter OTP</h3>
                    <div class="theme-card">

                        {{-- Dev OTP info --}}
                        @if(session('info'))
                            <div class="alert alert-info mb-3" role="alert">
                                {!! session('info') !!}
                            </div>
                        @endif

                        <p class="mb-4 text-muted">
                            A 6-digit OTP has been sent to
                            <strong>+91 {{ preg_replace('/(\d{2})\d{6}(\d{2})/', '$1******$2', $phone) }}</strong>.
                            Please enter it below. OTP is valid for 10 minutes.
                        </p>

                        @php
                            $verifyRoute  = $type === 'register' ? route('register.verify.submit') : route('login.verify.submit');
                        @endphp

                        <form class="theme-form" action="{{ $verifyRoute }}" method="POST" id="otpForm">
                            @csrf
                            <div class="form-box">
                                <label class="form-label">Enter 6-Digit OTP</label>
                                <div class="d-flex gap-2 justify-content-start mb-2" id="otpBoxes">
                                    <input type="text" class="form-control text-center otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" style="width:48px;font-size:1.25rem;letter-spacing:.1rem;" autofocus>
                                    <input type="text" class="form-control text-center otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" style="width:48px;font-size:1.25rem;">
                                    <input type="text" class="form-control text-center otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" style="width:48px;font-size:1.25rem;">
                                    <input type="text" class="form-control text-center otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" style="width:48px;font-size:1.25rem;">
                                    <input type="text" class="form-control text-center otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" style="width:48px;font-size:1.25rem;">
                                    <input type="text" class="form-control text-center otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]" style="width:48px;font-size:1.25rem;">
                                </div>
                                {{-- Hidden field that holds the combined OTP --}}
                                <input type="hidden" name="otp" id="otpHidden">
                                @error('otp')
                                    <span class="text-danger d-block mt-1">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-solid" id="verifyBtn">Verify OTP</button>
                        </form>

                        <div class="mt-3">
                            <span class="text-muted">Didn't receive the OTP?</span>
                            <form action="{{ route('otp.resend') }}" method="POST" class="d-inline" id="resendForm">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 ms-1 text-color" id="resendBtn" disabled>
                                    Resend OTP <span id="resendTimer">(in <span id="countdown">30</span>s)</span>
                                </button>
                            </form>
                        </div>

                        <div class="mt-2">
                            @if($type === 'register')
                                <a href="{{ route('register') }}" class="text-color">
                                    <i class="ri-arrow-left-line"></i> Back to Registration
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-color">
                                    <i class="ri-arrow-left-line"></i> Back to Login
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->
@endsection

@push('scripts')
<script>
(function () {
    // OTP box auto-focus and combine
    const boxes = document.querySelectorAll('.otp-box');
    const hidden = document.getElementById('otpHidden');

    boxes.forEach(function (box, i) {
        box.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 1 && i < boxes.length - 1) {
                boxes[i + 1].focus();
            }
            combineOtp();
        });

        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                boxes[i - 1].focus();
            }
        });

        box.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            pasted.split('').forEach(function (ch, idx) {
                if (boxes[idx]) boxes[idx].value = ch;
            });
            if (boxes[pasted.length - 1]) boxes[pasted.length - 1].focus();
            combineOtp();
        });
    });

    function combineOtp() {
        hidden.value = Array.from(boxes).map(function (b) { return b.value; }).join('');
    }

    document.getElementById('otpForm').addEventListener('submit', function () {
        combineOtp();
    });

    // Resend countdown
    let seconds = 30;
    const countdownEl = document.getElementById('countdown');
    const resendBtn   = document.getElementById('resendBtn');
    const resendTimer = document.getElementById('resendTimer');

    const timer = setInterval(function () {
        seconds--;
        countdownEl.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            resendTimer.style.display = 'none';
        }
    }, 1000);
})();
</script>
@endpush
