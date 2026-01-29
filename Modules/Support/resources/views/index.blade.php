@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Support Tickets</h4>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket #</th>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>
                                    {{ $ticket->name }}<br>
                                    <small class="text-muted">{{ $ticket->email }}</small>
                                </td>
                                <td>{{ Str::limit($ticket->subject, 30) }}</td>
                                <td>{{ $ticket->category }}</td>
                                <td>
                                    @if($ticket->status == 'open')
                                        <span class="badge bg-success-subtle text-success">Open</span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span class="badge bg-warning-subtle text-warning">In Progress</span>
                                    @elseif($ticket->status == 'resolved')
                                        <span class="badge bg-info-subtle text-info">Resolved</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->priority == 'high')
                                        <span class="badge bg-danger">High</span>
                                    @elseif($ticket->priority == 'medium')
                                        <span class="badge bg-warning">Medium</span>
                                    @else
                                        <span class="badge bg-info">Low</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.support.tickets.show', $ticket->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-eye"></i> View</a>
                                        <form action="{{ route('admin.support.tickets.destroy', $ticket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No tickets found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                   {{ $tickets->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
