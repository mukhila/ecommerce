@extends('layouts.master')
@section('title', 'Contact Us | Jango Tailors')

@section('content')

    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>contact us</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Contact Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- Contact Info Cards -->
    <section class="section-b-space">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-card text-center p-4 h-100">
                        <div class="contact-icon-wrap mb-3">
                            <i class="ri-map-pin-line"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Our Address</h5>
                        <p class="text-muted mb-0">123 Main Bazaar, Virudhunagar,<br>Tamil Nadu, India - 626001</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-card text-center p-4 h-100">
                        <div class="contact-icon-wrap mb-3">
                            <i class="ri-phone-line"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Call Us</h5>
                        <p class="text-muted mb-1">+91 98765 43210</p>
                        <p class="text-muted mb-0">+91 98765 43211</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-card text-center p-4 h-100">
                        <div class="contact-icon-wrap mb-3">
                            <i class="ri-mail-line"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Email Us</h5>
                        <p class="text-muted mb-1">info@jangotailors.com</p>
                        <p class="text-muted mb-0">support@jangotailors.com</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="contact-form-wrap p-4 p-md-5">
                        <h3 class="fw-bold mb-2">Send Us a Message</h3>
                        <p class="text-muted mb-4">Have a question or need assistance? Fill out the form below and we'll get back to you as soon as possible.</p>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Your name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Your email" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Your phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Message subject" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-solid">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Business Hours & Map -->
                <div class="col-lg-5">
                    <div class="contact-hours-card p-4 mb-4">
                        <h5 class="fw-bold mb-3"><i class="ri-time-line me-2"></i>Business Hours</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>Monday - Friday</span>
                                <span class="fw-semibold">9:00 AM - 8:00 PM</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span>Saturday</span>
                                <span class="fw-semibold">9:00 AM - 6:00 PM</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span>Sunday</span>
                                <span class="fw-semibold text-danger">Closed</span>
                            </li>
                        </ul>
                    </div>

                    <div class="contact-map-placeholder">
                        <div class="map-embed rounded overflow-hidden" style="height: 280px; background: #e9ecef;">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3933.0!2d77.95!3d9.58!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOcKwMzQnNDguMCJOIDc3wrA1NycwMC4wIkU!5e0!3m2!1sen!2sin!4v1234567890"
                                width="100%"
                                height="280"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
