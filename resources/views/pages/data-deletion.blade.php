@extends('layouts.master')
@section('title', 'Data Deletion Request | Jango Kids')

@section('content')

    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>Data Deletion Request</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Data Deletion</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="section-b-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="mb-4">
                        <h3>Your Right to Data Deletion</h3>
                        <p class="text-muted">
                            Under applicable data protection laws, you have the right to request the deletion of your
                            personal data held by Jango Kids. Once your request is verified and processed, we will
                            permanently delete your account and all associated personal information from our systems.
                        </p>
                    </div>

                    <div class="card mb-4" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="card-body p-4">
                            <h5 class="mb-3">What will be deleted</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Your account and profile information</li>
                                <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Contact details (name, email, phone, address)</li>
                                <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Order history and purchase records</li>
                                <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Saved addresses and payment preferences</li>
                                <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Wishlist and saved items</li>
                                <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Reviews and ratings submitted</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mb-4" style="border: 1px solid #fff3cd; background-color: #fffbf0; border-radius: 8px;">
                        <div class="card-body p-4">
                            <h5 class="mb-2">Please Note</h5>
                            <p class="mb-1 text-muted">Certain data may be retained for a limited period as required by law (e.g., financial records for tax compliance). This data will not be used for any marketing or profiling purposes.</p>
                            <p class="mb-0 text-muted">Deletion requests are typically processed within <strong>30 days</strong>. You will receive a confirmation email once completed.</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="card-body p-4">
                            <h5 class="mb-3">Submit a Deletion Request</h5>
                            <form action="{{ route('data-deletion.submit') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                                        placeholder="Enter your full name" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                        placeholder="Enter your registered email" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason for Deletion <span class="text-muted">(optional)</span></label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3"
                                        placeholder="Let us know why you'd like your data deleted...">{{ old('reason') }}</textarea>
                                </div>

                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input @error('confirm') is-invalid @enderror"
                                        id="confirm" name="confirm" value="1" required>
                                    <label class="form-check-label" for="confirm">
                                        I understand that this action is <strong>permanent and irreversible</strong> and I want to proceed with deleting my data.
                                    </label>
                                    @error('confirm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <button type="submit" class="btn btn-solid">Submit Deletion Request</button>
                            </form>
                        </div>
                    </div>

                    <p class="mt-4 text-muted text-center">
                        Questions? Contact us at
                        <a href="mailto:support@jangokids.com">support@jangokids.com</a>
                        or visit our <a href="{{ route('contact') }}">contact page</a>.
                    </p>

                </div>
            </div>
        </div>
    </section>
    <!-- section End -->

@endsection
