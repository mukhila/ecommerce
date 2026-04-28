@extends('layouts.master')

@section('title', 'Verify Email | JangaKids')

@section('content')
<div class="breadcrumb-section">
    <div class="container">
        <h2>Verify Your Email</h2>
    </div>
</div>

<section class="section-b-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <i class="ri-mail-send-line" style="font-size:48px;color:#f0762a;"></i>
                    </div>
                    <h4 class="text-center mb-3">Check your inbox</h4>
                    <p class="text-muted text-center">
                        We sent a verification link to <strong>{{ Auth::user()->email }}</strong>.
                        Click the link in that email to verify your account.
                    </p>

                    @if(session('verification_resent'))
                        <div class="alert alert-success mt-3">A new verification link has been sent to your email.</div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}" class="text-center mt-3">
                        @csrf
                        <button type="submit" class="btn btn-solid">Resend Verification Email</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('dashboard') }}" class="text-muted small">Continue to dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
