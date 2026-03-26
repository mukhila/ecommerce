@extends('layouts.master')

@section('title', 'Ticket – ' . $ticket->ticket_number)

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Support Ticket</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('support.index') }}">My Tickets</a></li>
                    <li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end -->

    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Ticket Header -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="mb-1">{{ $ticket->subject }}</h5>
                                    <small class="text-muted">{{ $ticket->ticket_number }} &bull; Opened {{ $ticket->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <span class="badge bg-{{ $ticket->status_badge }} fs-6">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Conversation Thread -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Conversation</h6>
                        </div>
                        <div class="card-body p-0">

                            <!-- Original message -->
                            <div class="d-flex p-4 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-circle bg-primary text-white">
                                        {{ strtoupper(substr($ticket->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong>{{ $ticket->name }} <span class="badge bg-light text-dark fw-normal">You</span></strong>
                                        <small class="text-muted">{{ $ticket->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                    <p class="mb-0" style="white-space: pre-line;">{{ $ticket->message }}</p>
                                    @if($ticket->attachment)
                                        <div class="mt-2">
                                            <a href="{{ Storage::disk('public')->url($ticket->attachment) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="ri-attachment-line"></i> View Attachment
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Replies -->
                            @foreach($ticket->replies as $reply)
                                <div class="d-flex p-4 border-bottom {{ $reply->is_admin ? 'bg-light' : '' }}">
                                    <div class="flex-shrink-0 me-3">
                                        @if($reply->is_admin)
                                            <div class="avatar-circle bg-success text-white">S</div>
                                        @else
                                            <div class="avatar-circle bg-primary text-white">
                                                {{ strtoupper(substr($ticket->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong>
                                                @if($reply->is_admin)
                                                    Support Team <span class="badge bg-success text-white fw-normal">Staff</span>
                                                @else
                                                    {{ $ticket->name }} <span class="badge bg-light text-dark fw-normal">You</span>
                                                @endif
                                            </strong>
                                            <small class="text-muted">{{ $reply->created_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                        <p class="mb-0" style="white-space: pre-line;">{{ $reply->message }}</p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!-- Reply Form -->
                    @if(!in_array($ticket->status, ['resolved', 'closed']))
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Send a Follow-up</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('support.reply', $ticket->ticket_number) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea class="form-control @error('message') is-invalid @enderror"
                                                  name="message"
                                                  rows="5"
                                                  placeholder="Type your follow-up message here..."
                                                  required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-solid">
                                        <i class="ri-send-plane-line"></i> Send Reply
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary text-center">
                            <i class="ri-lock-line"></i>
                            This ticket is <strong>{{ ucfirst($ticket->status) }}</strong>.
                            If you still need help, please <a href="{{ route('support.create') }}">open a new ticket</a>.
                        </div>
                    @endif

                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Ticket Details</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-5">Status</dt>
                                <dd class="col-7">
                                    <span class="badge bg-{{ $ticket->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </dd>

                                <dt class="col-5">Priority</dt>
                                <dd class="col-7">
                                    <span class="badge bg-{{ $ticket->priority_badge }}">{{ ucfirst($ticket->priority) }}</span>
                                </dd>

                                <dt class="col-5">Category</dt>
                                <dd class="col-7">{{ $ticket->category }}</dd>

                                @if($ticket->order_id)
                                    <dt class="col-5">Order</dt>
                                    <dd class="col-7">{{ $ticket->order_id }}</dd>
                                @endif

                                <dt class="col-5">Opened</dt>
                                <dd class="col-7"><small>{{ $ticket->created_at->format('d M Y') }}</small></dd>

                                <dt class="col-5">Updated</dt>
                                <dd class="col-7"><small>{{ $ticket->updated_at->diffForHumans() }}</small></dd>
                            </dl>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <a href="{{ route('support.index') }}" class="btn btn-outline w-100 mb-2">
                                <i class="ri-arrow-left-line"></i> Back to My Tickets
                            </a>
                            <a href="{{ route('support.create') }}" class="btn btn-sm btn-outline-secondary w-100">
                                <i class="ri-add-line"></i> New Ticket
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    flex-shrink: 0;
}
</style>
