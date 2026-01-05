@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Ticket #{{ $ticket->id }}: {{ $ticket->subject }}</h4>
                    <div>
                        <form action="{{ route('admin.support.tickets.update', $ticket->id) }}" method="POST" class="d-inline-flex gap-2">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm">
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update Status</button>
                        </form>
                    </div>
                </div>

                <div class="chat-conversation">
                    <ul class="list-unstyled mb-0" style="max-height: 500px; overflow-y: auto;">
                        @foreach($ticket->messages as $message)
                            <li class="{{ $message->user_id == $ticket->user_id ? 'right' : '' }}">
                                <div class="conversation-list">
                                    <div class="ctext-wrap">
                                        <div class="conversation-name">{{ $message->user_id == $ticket->user_id ? 'User' : 'Support Agent' }}</div>
                                        <p>{{ $message->message }}</p>
                                        <p class="chat-time mb-0"><i class="mdi mdi-clock-outline me-1"></i> {{ $message->created_at->format('M d, g:i A') }}</p>
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
