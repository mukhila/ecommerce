@extends('layouts.master')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Customer's login</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Customer's login</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3>Login</h3>
                    <div class="theme-card">
                        <form class="theme-form" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-box">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required="">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-box">
                                <label for="password" class="form-label">Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required="">
                                    <i class="ri-eye-line position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword" style="cursor: pointer;"></i>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-solid">Login</button>
                            <div class="mt-3">
                                <a href="{{ route('password.request') }}" class="text-color">Forgot your password?</a>
                            </div>
                        </form>
                    </div>
                </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('ri-eye-line');
            this.classList.toggle('ri-eye-off-line');
        });
    </script>

                <div class="col-lg-6 right-login">
                    <h3>New Customer</h3>
                    <div class="theme-card authentication-right">
                        <h6 class="title-font">Create A Account</h6>
                        <p>Sign up for a free account at our store. Registration is quick and easy. It allows you to be
                            able to order from our shop. To start shopping click register.</p>
                        <a href="{{ route('register') }}" class="btn btn-solid">Create an Account</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->
@endsection
