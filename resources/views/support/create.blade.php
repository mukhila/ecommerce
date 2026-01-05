@extends('layouts.master')

@section('title', 'Submit Support Ticket')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Support Center</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Submit Ticket</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!--section start-->
    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="ri-customer-service-2-line" style="font-size: 60px; color: #ff6f61;"></i>
                                <h3 class="mt-3">How Can We Help You?</h3>
                                <p class="text-muted">Fill out the form below and our support team will get back to you as soon as possible.</p>
                            </div>

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name"
                                               value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email"
                                               value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category') is-invalid @enderror"
                                                id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>General Inquiry</option>
                                            <option value="Order Issue" {{ old('category') == 'Order Issue' ? 'selected' : '' }}>Order Issue</option>
                                            <option value="Payment" {{ old('category') == 'Payment' ? 'selected' : '' }}>Payment Problem</option>
                                            <option value="Product" {{ old('category') == 'Product' ? 'selected' : '' }}>Product Question</option>
                                            <option value="Returns" {{ old('category') == 'Returns' ? 'selected' : '' }}>Returns & Refunds</option>
                                            <option value="Shipping" {{ old('category') == 'Shipping' ? 'selected' : '' }}>Shipping & Delivery</option>
                                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                        <select class="form-select @error('priority') is-invalid @enderror"
                                                id="priority" name="priority" required>
                                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - General question</option>
                                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium - Need assistance</option>
                                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Urgent issue</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @if(Auth::check() && $userOrders->count() > 0)
                                    <div class="mb-3">
                                        <label for="order_id" class="form-label">Related Order (Optional)</label>
                                        <select class="form-select @error('order_id') is-invalid @enderror"
                                                id="order_id" name="order_id">
                                            <option value="">None - Not related to an order</option>
                                            @foreach($userOrders as $order)
                                                <option value="{{ $order->order_number }}" {{ old('order_id') == $order->order_number ? 'selected' : '' }}>
                                                    {{ $order->order_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('order_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                           id="subject" name="subject"
                                           value="{{ old('subject') }}"
                                           placeholder="Brief description of your issue"
                                           required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror"
                                              id="message" name="message"
                                              rows="6"
                                              placeholder="Please provide as much detail as possible about your issue..."
                                              required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Attachment (Optional)</label>
                                    <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                           id="attachment" name="attachment"
                                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                    <small class="text-muted">Accepted formats: JPG, PNG, PDF, DOC, DOCX (Max 2MB)</small>
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-solid btn-lg">
                                        <i class="ri-send-plane-line"></i> Submit Ticket
                                    </button>
                                    <a href="{{ route('home') }}" class="btn btn-outline">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- FAQ Quick Links -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Need Quick Answers?</h5>
                            <p class="text-muted">Check our FAQ section for instant answers to common questions.</p>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="ri-question-line"></i> Visit FAQ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection
