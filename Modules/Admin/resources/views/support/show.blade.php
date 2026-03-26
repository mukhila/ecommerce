@extends('admin::layouts.master')

@section('title', 'Ticket – ' . $ticket->ticket_number)

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">{{ $ticket->ticket_number }}</h1>
                <p class="text-muted mb-0">{{ $ticket->subject }}</p>
            </div>
            <a href="{{ route('admin.support.index') }}" class="btn btn-secondary btn-sm">
                <i class="ri-arrow-left-line"></i> Back to Tickets
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        <!-- Left: Conversation -->
        <div class="col-lg-8">

            <!-- Conversation Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Conversation</h6>
                    <span class="badge badge-{{ $ticket->status_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </div>
                <div class="card-body p-0">

                    <!-- Original Message -->
                    <div class="d-flex p-4 border-bottom">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-circle bg-secondary text-white">
                                {{ strtoupper(substr($ticket->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong>{{ $ticket->name }}
                                    <span class="badge badge-light text-dark font-weight-normal">Customer</span>
                                </strong>
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
                                    <div class="avatar-circle bg-primary text-white">A</div>
                                @else
                                    <div class="avatar-circle bg-secondary text-white">
                                        {{ strtoupper(substr($ticket->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong>
                                        @if($reply->is_admin)
                                            Support Agent <span class="badge badge-primary font-weight-normal">Staff</span>
                                        @else
                                            {{ $ticket->name }} <span class="badge badge-light text-dark font-weight-normal">Customer</span>
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

            <!-- Admin Reply Form -->
            @if(!in_array($ticket->status, ['closed']))
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Reply to Customer</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.support.reply', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="message"
                                          class="form-control @error('message') is-invalid @enderror"
                                          rows="5"
                                          placeholder="Type your reply here... (min. 10 characters)"
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">The customer will be notified by email.</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-send-plane-line"></i> Send Reply
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>

        <!-- Right: Management Sidebar -->
        <div class="col-lg-4">

            <!-- Customer Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><i class="ri-user-line me-2 text-muted"></i><strong>{{ $ticket->name }}</strong></p>
                    <p class="mb-1"><i class="ri-mail-line me-2 text-muted"></i>
                        <a href="mailto:{{ $ticket->email }}">{{ $ticket->email }}</a>
                    </p>
                    @if($ticket->phone)
                        <p class="mb-1"><i class="ri-phone-line me-2 text-muted"></i>{{ $ticket->phone }}</p>
                    @endif
                    @if($ticket->user)
                        <p class="mb-0">
                            <a href="{{ route('admin.customers.show', $ticket->user->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                View Customer Profile
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Ticket Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Category</td>
                            <td><span class="badge badge-secondary">{{ $ticket->category }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Priority</td>
                            <td><span class="badge badge-{{ $ticket->priority_badge }}">{{ ucfirst($ticket->priority) }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td><span class="badge badge-{{ $ticket->status_badge }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span></td>
                        </tr>
                        @if($ticket->order_id)
                            <tr>
                                <td class="text-muted">Order</td>
                                <td>{{ $ticket->order_id }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Opened</td>
                            <td><small>{{ $ticket->created_at->format('d M Y') }}</small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Updated</td>
                            <td><small>{{ $ticket->updated_at->diffForHumans() }}</small></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Replies</td>
                            <td>{{ $ticket->replies->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Update Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.support.update-status', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <select name="status" class="form-control">
                                <option value="open"        {{ $ticket->status == 'open'        ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved"    {{ $ticket->status == 'resolved'    ? 'selected' : '' }}>Resolved</option>
                                <option value="closed"      {{ $ticket->status == 'closed'      ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-sm">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Assign Ticket -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Assign To</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.support.assign', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <select name="assigned_to" class="form-control">
                                <option value="">Unassigned</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-block btn-sm">Assign</button>
                    </form>
                    @if($ticket->assignedAdmin)
                        <small class="text-muted mt-1 d-block">
                            Currently: <strong>{{ $ticket->assignedAdmin->name }}</strong>
                        </small>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
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
