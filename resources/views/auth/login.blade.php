@extends('layouts.master')

@push('styles')
<style>
/* ── Auth Tabs ── */
.auth-tabs-wrapper { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 20px rgba(0,0,0,.08); }
.auth-nav-tabs { border-bottom: 2px solid #f0f0f0; margin-bottom: 0; }
.auth-nav-tabs .nav-link {
    font-size: 15px; font-weight: 600; color: #777;
    padding: 14px 28px; border: none; border-bottom: 3px solid transparent;
    background: none; border-radius: 0; transition: all .25s;
}
.auth-nav-tabs .nav-link.active { color: var(--theme-color, #ff4c3b); border-bottom-color: var(--theme-color, #ff4c3b); }
.auth-tab-body { padding: 30px; }

/* ── Social Buttons ── */
.btn-social-auth {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 11px 18px; font-size: 14px; font-weight: 600;
    border-radius: 6px; text-decoration: none; transition: all .2s; border: none; cursor: pointer;
}
.btn-google-auth { background:#fff; color:#3c4043; border:1.5px solid #dadce0; }
.btn-google-auth:hover { background:#f8f9fa; color:#3c4043; box-shadow:0 1px 6px rgba(0,0,0,.15); }
.btn-facebook-auth { background:#1877f2; color:#fff; }
.btn-facebook-auth:hover { background:#166fe5; color:#fff; }

/* ── Divider ── */
.auth-divider { display:flex; align-items:center; gap:12px; margin:22px 0; }
.auth-divider hr { flex:1; margin:0; border-color:#e5e5e5; }
.auth-divider span { font-size:12px; color:#aaa; font-weight:500; white-space:nowrap; }

/* ── Benefits list ── */
.benefit-list li { padding: 6px 0; font-size: 14px; color: #555; }
.benefit-list li i { color: #28a745; margin-right: 8px; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-section">
    <div class="container">
        <h2>My Account</h2>
        <nav class="theme-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Login / Register</li>
            </ol>
        </nav>
    </div>
</div>

<section class="login-page section-b-space">
    <div class="container">
        <div class="row justify-content-center">

            {{-- ── Auth Card ── --}}
            <div class="col-lg-7 col-xl-6">
                <div class="auth-tabs-wrapper">

                    {{-- Tab Navigation --}}
                    <ul class="nav auth-nav-tabs" role="tablist" id="authTabs">
                        <li class="nav-item">
                            <button class="nav-link active" id="login-tab-btn" data-bs-toggle="tab"
                                    data-bs-target="#login-tab" type="button" role="tab">
                                Sign In
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="register-tab-btn" data-bs-toggle="tab"
                                    data-bs-target="#register-tab" type="button" role="tab">
                                Create Account
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- ════════════════════════════════════
                             TAB 1 — SIGN IN
                        ════════════════════════════════════ --}}
                        <div class="tab-pane fade show active auth-tab-body" id="login-tab" role="tabpanel">

                            @if ($errors->has('session'))
                                <div class="alert alert-warning">{{ $errors->first('session') }}</div>
                            @endif

                            @if ($errors->has('social'))
                                <div class="alert alert-danger">{{ $errors->first('social') }}</div>
                            @endif

                            {{-- Social Login --}}
                            <p class="text-muted small mb-3">Sign in instantly with your social account.</p>
                            <div class="d-grid gap-2 mb-1">
                                <a href="{{ route('auth.google') }}" class="btn-social-auth btn-google-auth">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                    </svg>
                                    Continue with Google
                                </a>
                                <a href="{{ route('auth.facebook') }}" class="btn-social-auth btn-facebook-auth">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                                        <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.413c0-3.026 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.513c-1.491 0-1.956.93-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                                    </svg>
                                    Continue with Facebook
                                </a>
                            </div>

                            <div class="auth-divider">
                                <hr><span>OR LOGIN WITH MOBILE OTP</span><hr>
                            </div>

                            {{-- OTP Login Form --}}
                            <form action="{{ route('login.send-otp') }}" method="POST">
                                @csrf
                                <div class="form-box mb-3">
                                    <label class="form-label fw-600">Mobile Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+91</span>
                                        <input type="tel"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" value="{{ old('phone') }}"
                                               placeholder="10-digit mobile number"
                                               maxlength="10" inputmode="numeric"
                                               pattern="[6-9][0-9]{9}" required>
                                    </div>
                                    @error('phone')
                                        <span class="text-danger d-block mt-1 small"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <small class="text-muted">We'll send a 6-digit OTP to verify.</small>
                                </div>

                                {{-- reCAPTCHA --}}
                                <div class="form-box mb-3">
                                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                    @error('g-recaptcha-response')
                                        <span class="text-danger d-block mt-1 small"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @error('recaptcha')
                                        <span class="text-danger d-block mt-1 small"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-solid w-100">Send OTP &amp; Sign In</button>
                            </form>

                            <p class="text-center mt-3 mb-0 small text-muted">
                                Don't have an account?
                                <a href="#" class="text-color fw-600" id="switchToRegister">Create one free</a>
                            </p>
                        </div>
                        {{-- END Sign In Tab --}}


                        {{-- ════════════════════════════════════
                             TAB 2 — CREATE ACCOUNT
                        ════════════════════════════════════ --}}
                        <div class="tab-pane fade auth-tab-body" id="register-tab" role="tabpanel">

                            {{-- Social Register --}}
                            <p class="text-muted small mb-3">Sign up instantly — no password needed.</p>
                            <div class="d-grid gap-2 mb-1">
                                <a href="{{ route('auth.google') }}" class="btn-social-auth btn-google-auth">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                    </svg>
                                    Sign up with Google
                                </a>
                                <a href="{{ route('auth.facebook') }}" class="btn-social-auth btn-facebook-auth">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                                        <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.413c0-3.026 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.513c-1.491 0-1.956.93-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                                    </svg>
                                    Sign up with Facebook
                                </a>
                            </div>

                            <div class="auth-divider">
                                <hr><span>OR REGISTER WITH MOBILE OTP</span><hr>
                            </div>

                            {{-- Registration Form --}}
                            <form action="{{ route('register.send-otp') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-box mb-3">
                                            <label class="form-label fw-600">First Name</label>
                                            <input type="text"
                                                   class="form-control @error('fname') is-invalid @enderror"
                                                   name="fname" value="{{ old('fname') }}"
                                                   placeholder="First name" required>
                                            @error('fname')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-box mb-3">
                                            <label class="form-label fw-600">Last Name</label>
                                            <input type="text"
                                                   class="form-control @error('lname') is-invalid @enderror"
                                                   name="lname" value="{{ old('lname') }}"
                                                   placeholder="Last name" required>
                                            @error('lname')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-box mb-3">
                                    <label class="form-label fw-600">Email Address</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}"
                                           placeholder="you@example.com" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-box mb-3">
                                    <label class="form-label fw-600">Mobile Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+91</span>
                                        <input type="tel"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" value="{{ old('phone') }}"
                                               placeholder="10-digit mobile number"
                                               maxlength="10" inputmode="numeric"
                                               pattern="[6-9][0-9]{9}" required>
                                    </div>
                                    @error('phone')
                                        <span class="text-danger d-block mt-1 small">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">OTP will be sent to verify your number.</small>
                                </div>

                                <button type="submit" class="btn btn-solid w-100">Send OTP &amp; Create Account</button>
                            </form>

                            <ul class="benefit-list list-unstyled mt-4 mb-0">
                                <li><i class="fa fa-check-circle"></i> Track your orders in real-time</li>
                                <li><i class="fa fa-check-circle"></i> Save wishlist &amp; reorder easily</li>
                                <li><i class="fa fa-check-circle"></i> Faster checkout experience</li>
                                <li><i class="fa fa-check-circle"></i> Exclusive member-only offers</li>
                            </ul>

                            <p class="text-center mt-3 mb-0 small text-muted">
                                Already have an account?
                                <a href="#" class="text-color fw-600" id="switchToLogin">Sign in here</a>
                            </p>
                        </div>
                        {{-- END Create Account Tab --}}

                    </div>{{-- end tab-content --}}
                </div>{{-- end auth-tabs-wrapper --}}
            </div>{{-- end col --}}

        </div>{{-- end row --}}
    </div>{{-- end container --}}
</section>

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
(function () {
    // Determine which tab to activate on page load
    var activeTab = '{{ session('active_tab', 'login') }}';

    @if ($errors->has('fname') || $errors->has('lname') || $errors->has('email'))
        activeTab = 'register';
    @endif

    @if (request('tab') === 'register')
        activeTab = 'register';
    @endif

    if (activeTab === 'register') {
        document.getElementById('register-tab-btn').click();
    }

    // Cross-tab switch links
    document.getElementById('switchToRegister').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('register-tab-btn').click();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    document.getElementById('switchToLogin').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('login-tab-btn').click();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();
</script>
@endpush

@endsection
