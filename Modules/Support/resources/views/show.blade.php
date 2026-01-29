@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Ticket #{{ $ticket->ticket_number }}: {{ $ticket->subject }}</h4>
                    <div>
                        <form action="{{ route('admin.support.tickets.update', $ticket->id) }}" method="POST" class="d-inline-flex gap-2">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update Status</button>
                        </form>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                         <strong>Customer:</strong> <br> {{ $ticket->name }} <br> <small>{{ $ticket->email }}</small>
                    </div>
                    <div class="col-md-3">
                         <strong>Category:</strong> <br> {{ $ticket->category }}
                    </div>
                     <div class="col-md-3">
                         <strong>Order ID:</strong> <br> {{ $ticket->order_id ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                         <strong>Priority:</strong> <br> 
                         <span class="badge bg-{{ $ticket->priority_badge }}">{{ ucfirst($ticket->priority) }}</span>
                    </div>
                </div>
                
                 @if($ticket->attachment)
                    <div class="mb-4">
                        <strong>Attachment:</strong> <a href="{{ Storage::disk('public')->url($ticket->attachment) }}" target="_blank">View File</a>
                    </div>
                @endif
                
                <hr>
                
                <h5 class="mb-3">Conversation</h5>

                <div class="chat-conversation">
                    <ul class="list-unstyled mb-0" style="max-height: 500px; overflow-y: auto;">
                        <!-- Initial Message -->
                        <li class="left">
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="conversation-name">{{ $ticket->name }} (User)</div>
                                    <p>{{ $ticket->message }}</p>
                                    <p class="chat-time mb-0"><i class="mdi mdi-clock-outline me-1"></i> {{ $ticket->created_at->format('M d, g:i A') }}</p>
                                </div>
                            </div>
                        </li>

                        @foreach($ticket->replies as $reply)
                            <li class="{{ $reply->is_admin ? 'right' : 'left' }}">
                                <div class="conversation-list">
                                    <div class="ctext-wrap">
                                        <div class="conversation-name">{{ $reply->is_admin ? 'Support Agent' : $ticket->name }}</div>
                                        <p>{{ $reply->message }}</p>
                                        <p class="chat-time mb-0"><i class="mdi mdi-clock-outline me-1"></i> {{ $reply->created_at->format('M d, g:i A') }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4">
                    <form action="{{ route('admin.support.tickets.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="position-relative">
                                    <textarea name="message" class="form-control chat-input" placeholder="Enter Message..." required></textarea>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary chat-send w-md waves-effect waves-light"><span class="d-none d-sm-inline-block me-2">Send</span> <i class="mdi mdi-send"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
