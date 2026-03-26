@extends('layouts.master')

@section('title', 'My Support Tickets')

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>My Support Tickets</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">My Tickets</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end -->

    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Support Tickets</h4>
                        <a href="{{ route('support.create') }}" class="btn btn-solid">
                            <i class="ri-add-line"></i> New Ticket
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($tickets->isEmpty())
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ri-customer-service-2-line" style="font-size: 60px; color: #ccc;"></i>
                                <h5 class="mt-3 text-muted">No tickets yet</h5>
                                <p class="text-muted">Have a question or issue? Submit a support ticket and we'll help you.</p>
                                <a href="{{ route('support.create') }}" class="btn btn-solid mt-2">Submit a Ticket</a>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Ticket #</th>
                                                <th>Subject</th>
                                                <th>Category</th>
                                                <th>Priority</th>
                                                <th>Status</th>
                                                <th>Last Updated</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tickets as $ticket)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('support.show', $ticket->ticket_number) }}" class="fw-bold text-decoration-none">
                                                            {{ $ticket->ticket_number }}
                                                        </a>
                                                    </td>
                                                    <td>{{ Str::limit($ticket->subject, 60) }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $ticket->category }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $ticket->priority_badge }}">{{ ucfirst($ticket->priority) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $ticket->status_badge }}">
                                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $ticket->updated_at->diffForHumans() }}</small>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('support.show', $ticket->ticket_number) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="ri-eye-line"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
