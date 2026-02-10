@extends('admin::layouts.main')

@section('title', 'View Message')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title">View Message</h4>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i> Back to Messages
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $message->subject }}</h5>
                    @if($message->is_read)
                        <span class="badge bg-success">Read</span>
                    @else
                        <span class="badge bg-warning text-dark">Unread</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-4" style="white-space: pre-wrap;">{{ $message->message }}</div>

                    <hr>
                    <small class="text-muted">Received on {{ $message->created_at->format('F d, Y \a\t h:i A') }}</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sender Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-nowrap mb-0">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $message->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $message->phone ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100"><i class="mdi mdi-delete me-1"></i> Delete Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
