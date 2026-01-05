@extends('layouts.master')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Create account</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Create account</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <h3>create account</h3>
            <div class="theme-card">
                <form class="theme-form" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-box">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('fname') is-invalid @enderror" id="fname" name="fname" value="{{ old('fname') }}" placeholder="First Name" required="">
                                @error('fname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-box">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('lname') is-invalid @enderror" id="lname" name="lname" value="{{ old('lname') }}" placeholder="Last Name" required="">
                                @error('lname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-box">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required="">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-box">
                                <label for="password" class="form-label">Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required="">
                                    <i class="ri-eye-line position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword" style="cursor: pointer;"></i>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-box">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required="">
                                    <i class="ri-eye-line position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-solid w-auto">Create Account</button>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                document.getElementById('togglePassword').addEventListener('click', function (e) {
                    const passwordInput = document.getElementById('password');
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('ri-eye-line');
                    this.classList.toggle('ri-eye-off-line');
                });

                document.getElementById('toggleConfirmPassword').addEventListener('click', function (e) {
                    const passwordInput = document.getElementById('password_confirmation');
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('ri-eye-line');
                    this.classList.toggle('ri-eye-off-line');
                });
            </script>
        </div>
    </section>
    <!--Section ends-->
@endsection
