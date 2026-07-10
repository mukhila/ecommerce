@extends('layouts.master')
@section('title', 'Shipping Policy | Jango Kidswear')

@section('content')

    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>Shipping Policy</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Shipping Policy</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="about-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="mb-4">Shipping Policy</h2>
                    <p><strong>Jango Kidswear</strong> is committed to delivering your orders quickly and safely across India.</p>

                    <h5 class="mt-4">Delivery Timelines</h5>
                    <ul>
                        <li><strong>Standard Delivery:</strong> 5–7 business days</li>
                        <li><strong>Express Delivery:</strong> 2–3 business days (where available)</li>
                    </ul>

                    <h5 class="mt-4">Shipping Charges</h5>
                    <ul>
                        <li><strong>Free Shipping</strong> on all orders above ₹3,000.</li>
                        <li>A flat shipping fee of ₹60 applies to orders below ₹3,000.</li>
                    </ul>

                    <h5 class="mt-4">Order Processing</h5>
                    <ul>
                        <li>Orders are processed within 1–2 business days after payment confirmation.</li>
                        <li>Orders placed on weekends or public holidays will be processed the next working day.</li>
                        <li>Tracking details will be sent to your registered email and phone number once dispatched.</li>
                    </ul>

                    <h5 class="mt-4">Delivery Coverage</h5>
                    <p>We ship to all major cities and pin codes across India. For remote areas, delivery may take an additional 2–3 days.</p>

                    <h5 class="mt-4">Damaged or Lost Shipments</h5>
                    <p>If your package arrives damaged or is lost in transit, please contact us within 48 hours of the expected delivery date. We will raise a complaint with the courier and arrange a replacement or refund.</p>

                    <h5 class="mt-4">Contact Us</h5>
                    <p>For any shipping queries, contact us at <a href="mailto:support@jangokids.com">support@jangokids.com</a> or call <strong>+91 98765 43210</strong>.</p>
                </div>
            </div>
        </div>
    </section>

@endsection
