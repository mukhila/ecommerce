@extends('layouts.master')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Create Account</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Create Account</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <h3>Create Account</h3>
            <div class="theme-card">
                <form class="theme-form" action="{{ route('register.send-otp') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-box">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('fname') is-invalid @enderror" id="fname" name="fname" value="{{ old('fname') }}" placeholder="First Name" required>
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
                                <input type="text" class="form-control @error('lname') is-invalid @enderror" id="lname" name="lname" value="{{ old('lname') }}" placeholder="Last Name" required>
                                @error('lname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-box">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-box">
                                <label for="phone" class="form-label">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input
                                        type="tel"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="10-digit mobile number"
                                        maxlength="10"
                                        inputmode="numeric"
                                        pattern="[6-9][0-9]{9}"
                                        required
                                    >
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="text-muted mt-1 d-block">We will send a 6-digit OTP to verify your number.</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-solid w-auto">Send OTP &amp; Create Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!--Section ends-->
@endsection
