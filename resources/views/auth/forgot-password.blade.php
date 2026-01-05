@extends('layouts.master')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Forgot password</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Forgot password</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="pwd-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <h2>Forget Your Password</h2>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form class="theme-form" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="form-row row">
                            <div class="col-md-12">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter Your Email" required="">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-solid w-auto">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->
@endsection
