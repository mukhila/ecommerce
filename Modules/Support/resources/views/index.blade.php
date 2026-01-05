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
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>
                                    @if($ticket->status == 'Open')
                                        <span class="badge bg-success-subtle text-success">Open</span>
                                    @elseif($ticket->status == 'In Progress')
                                        <span class="badge bg-warning-subtle text-warning">In Progress</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->priority == 'High')
                                        <span class="badge bg-danger">{{ $ticket->priority }}</span>
                                    @elseif($ticket->priority == 'Medium')
                                        <span class="badge bg-warning">{{ $ticket->priority }}</span>
                                    @else
                                        <span class="badge bg-info">{{ $ticket->priority }}</span>
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
                                <td colspan="6" class="text-center">No tickets found.</td>
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
