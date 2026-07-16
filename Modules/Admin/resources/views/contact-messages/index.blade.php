@extends('admin::layouts.main')

@section('title', 'Contact Messages')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Contact Messages</h4>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Messages</h5>
                    <h2 class="mb-0">{{ $messages->total() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Unread</h5>
                    <h2 class="mb-0 text-warning">{{ $unreadCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Bulk action toolbar (shown only when rows are selected) --}}
                    <div id="bulkToolbar" class="d-none mb-3 d-flex align-items-center gap-3 p-3 bg-light rounded border">
                        <span id="selectedCount" class="fw-semibold text-dark">0 selected</span>
                        <form id="bulkDeleteForm" action="{{ route('admin.contact-messages.bulk-delete') }}" method="POST"
                              onsubmit="return confirmBulkDelete()">
                            @csrf
                            <div id="bulkIds"></div>
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="mdi mdi-delete me-1"></i> Delete Selected
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="selectAll" class="form-check-input"
                                               title="Select all on this page">
                                    </th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $msg)
                                <tr class="{{ !$msg->is_read ? 'fw-bold' : '' }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input row-check"
                                               value="{{ $msg->id }}">
                                    </td>
                                    <td>#{{ $msg->id }}</td>
                                    <td>{{ $msg->name }}</td>
                                    <td>{{ $msg->email }}</td>
                                    <td>{{ Str::limit($msg->subject, 40) }}</td>
                                    <td>{{ $msg->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        @if($msg->is_read)
                                            <span class="badge bg-success">Read</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Unread</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-eye"></i> View</a>
                                            <form action="{{ route('admin.contact-messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Delete this message?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No contact messages found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $messages->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const selectAll   = document.getElementById('selectAll');
    const bulkToolbar = document.getElementById('bulkToolbar');
    const selectedCount = document.getElementById('selectedCount');
    const bulkIds     = document.getElementById('bulkIds');

    function getChecked() {
        return [...document.querySelectorAll('.row-check:checked')];
    }

    function updateToolbar() {
        const checked = getChecked();
        if (checked.length > 0) {
            bulkToolbar.classList.remove('d-none');
            selectedCount.textContent = checked.length + ' selected';
        } else {
            bulkToolbar.classList.add('d-none');
        }
        // Sync select-all state
        const all = document.querySelectorAll('.row-check');
        selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
        selectAll.checked = all.length > 0 && checked.length === all.length;
    }

    // Select all / deselect all
    selectAll.addEventListener('change', function () {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
        updateToolbar();
    });

    // Individual checkboxes
    document.querySelectorAll('.row-check').forEach(cb => {
        cb.addEventListener('change', updateToolbar);
    });

    // Populate hidden inputs before submit
    window.confirmBulkDelete = function () {
        const checked = getChecked();
        if (checked.length === 0) return false;
        if (!confirm('Delete ' + checked.length + ' message(s)? This cannot be undone.')) return false;
        bulkIds.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'ids[]';
            input.value = cb.value;
            bulkIds.appendChild(input);
        });
        return true;
    };
})();
</script>
@endpush
@endsection
