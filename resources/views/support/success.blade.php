@extends('layouts.master')

@section('title', 'Ticket Submitted Successfully')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Support Ticket Submitted</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('support.create') }}">Support</a></li>
                    <li class="breadcrumb-item active">Ticket Submitted</li>
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
                    <!-- Success Message -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="success-icon mb-4">
                                <i class="ri-checkbox-circle-line" style="font-size: 80px; color: #28a745;"></i>
                            </div>

                            <h2 class="mb-3">Ticket Submitted Successfully!</h2>
                            <p class="text-muted mb-4">
                                Thank you for contacting us. We've received your support request and will get back to you as soon as possible.
                            </p>

                            <!-- Ticket Number -->
                            <div class="ticket-number-box mb-4">
                                <div class="alert alert-info d-inline-block px-5 py-3">
                                    <h5 class="mb-2">Your Ticket Number</h5>
                                    <h2 class="mb-0 text-primary">{{ $ticket->ticket_number }}</h2>
                                </div>
                                <p class="text-muted mt-2">
                                    <small>Please save this ticket number for future reference</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Ticket Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Category:</strong></p>
                                    <span class="badge bg-primary">{{ $ticket->category }}</span>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Priority:</strong></p>
                                    <span class="badge bg-{{ $ticket->priority_badge }}">{{ ucfirst($ticket->priority) }}</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Status:</strong></p>
                                    <span class="badge bg-{{ $ticket->status_badge }}">{{ ucfirst($ticket->status) }}</span>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Submitted:</strong></p>
                                    <p class="mb-0">{{ $ticket->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>

                            @if($ticket->order_id)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p class="mb-1"><strong>Related Order:</strong></p>
                                        <p class="mb-0">{{ $ticket->order_id }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-1"><strong>Subject:</strong></p>
                                    <p class="mb-0">{{ $ticket->subject }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Response Time Information -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="mb-2">
                                        <i class="ri-time-line text-primary"></i> Expected Response Time
                                    </h6>
                                    <p class="text-muted mb-md-0">
                                        @if($ticket->priority == 'high')
                                            Our support team will respond to your urgent request within <strong>2-4 hours</strong>.
                                        @elseif($ticket->priority == 'medium')
                                            Our support team will respond to your request within <strong>12-24 hours</strong>.
                                        @else
                                            Our support team will respond to your request within <strong>24-48 hours</strong>.
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <i class="ri-customer-service-2-fill" style="font-size: 48px; color: #ff6f61; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">What Happens Next?</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline-steps">
                                <div class="timeline-step mb-3">
                                    <div class="d-flex">
                                        <div class="timeline-icon">
                                            <i class="ri-mail-check-line text-success" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6>Email Confirmation</h6>
                                            <p class="text-muted mb-0">You'll receive a confirmation email at <strong>{{ $ticket->email }}</strong> with your ticket details.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-step mb-3">
                                    <div class="d-flex">
                                        <div class="timeline-icon">
                                            <i class="ri-user-settings-line text-primary" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6>Team Assignment</h6>
                                            <p class="text-muted mb-0">Your ticket will be assigned to the most appropriate support specialist.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-step mb-3">
                                    <div class="d-flex">
                                        <div class="timeline-icon">
                                            <i class="ri-question-answer-line text-info" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6>Response</h6>
                                            <p class="text-muted mb-0">Our team will review your issue and respond with a solution or request for more information.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-step">
                                    <div class="d-flex">
                                        <div class="timeline-icon">
                                            <i class="ri-checkbox-circle-line text-success" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6>Resolution</h6>
                                            <p class="text-muted mb-0">Once your issue is resolved, you'll receive a notification and the ticket will be closed.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card mt-4">
                        <div class="card-body text-center">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                @if(Auth::check())
                                    <a href="{{ route('support.show', $ticket->ticket_number) }}" class="btn btn-solid">
                                        <i class="ri-eye-line"></i> View Ticket Details
                                    </a>
                                    <a href="{{ route('support.index') }}" class="btn btn-outline">
                                        <i class="ri-file-list-line"></i> My Tickets
                                    </a>
                                @endif
                                <a href="{{ route('home') }}" class="btn btn-outline">
                                    <i class="ri-home-line"></i> Back to Home
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Help -->
                    <div class="alert alert-light mt-4 text-center">
                        <p class="mb-2">
                            <i class="ri-information-line"></i> <strong>Need immediate assistance?</strong>
                        </p>
                        <p class="mb-0 text-muted">
                            For urgent matters, you can also reach us at <strong>support@example.com</strong> or call <strong>+91 1234567890</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection

<style>
.success-icon i {
    animation: successPulse 2s ease-in-out infinite;
}

@keyframes successPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.ticket-number-box .alert {
    border-left: 5px solid #0056b3;
}

.timeline-steps {
    position: relative;
}

.timeline-step {
    position: relative;
}

.timeline-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 50%;
}
</style>
@endsection
