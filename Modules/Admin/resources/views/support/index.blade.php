@extends('admin::layouts.master')

@section('title', 'Support Tickets')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Support Tickets</h1>
            <p class="text-muted">Manage customer support requests and inquiries</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tickets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="ri-file-list-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Open</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['open'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="ri-mail-open-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="ri-timer-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Resolved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['resolved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="ri-checkbox-circle-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Closed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['closed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="ri-close-circle-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">High Priority</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['high_priority'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="ri-alert-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.support.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}"
                               placeholder="Ticket #, Subject, Email...">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="priority">Priority</label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category">
                            <option value="">All Categories</option>
                            <option value="General" {{ request('category') == 'General' ? 'selected' : '' }}>General</option>
                            <option value="Order Issue" {{ request('category') == 'Order Issue' ? 'selected' : '' }}>Order Issue</option>
                            <option value="Payment" {{ request('category') == 'Payment' ? 'selected' : '' }}>Payment</option>
                            <option value="Product" {{ request('category') == 'Product' ? 'selected' : '' }}>Product</option>
                            <option value="Returns" {{ request('category') == 'Returns' ? 'selected' : '' }}>Returns</option>
                            <option value="Shipping" {{ request('category') == 'Shipping' ? 'selected' : '' }}>Shipping</option>
                            <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="assigned_to">Assigned To</label>
                        <select class="form-control" id="assigned_to" name="assigned_to">
                            <option value="">All Tickets</option>
                            <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 mb-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Tickets ({{ $tickets->total() }})</h6>
            <div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="toggleBulkActions()">
                    <i class="ri-checkbox-multiple-line"></i> Bulk Actions
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Bulk Actions Bar (Hidden by default) -->
            <div id="bulkActionsBar" class="alert alert-info" style="display: none;">
                <form id="bulkActionForm" method="POST" action="{{ route('admin.support.bulk-update') }}">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <span id="selectedCount">0</span> tickets selected
                        </div>
                        <div class="col-md-6 text-right">
                            <select name="action" id="bulkAction" class="form-control d-inline-block" style="width: auto;">
                                <option value="">Select Action</option>
                                <option value="assign">Assign To</option>
                                <option value="status">Change Status</option>
                                <option value="delete">Delete</option>
                            </select>
                            <select name="assigned_to" id="bulkAssignTo" class="form-control d-inline-block" style="width: auto; display: none;">
                                <option value="">Select Admin</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                            <select name="status" id="bulkStatus" class="form-control d-inline-block" style="width: auto; display: none;">
                                <option value="">Select Status</option>
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelBulkActions()">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            @if($tickets->isEmpty())
                <div class="text-center py-5">
                    <i class="ri-inbox-line" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-3">No tickets found</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Ticket #</th>
                                <th>Subject</th>
                                <th>Customer</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="ticket-checkbox" name="ticket_ids[]" value="{{ $ticket->id }}">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.support.show', $ticket->ticket_number) }}">
                                            <strong>{{ $ticket->ticket_number }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        {{ Str::limit($ticket->subject, 50) }}
                                        @if($ticket->order_id)
                                            <br><small class="text-muted">Order: {{ $ticket->order_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $ticket->name }}<br>
                                        <small class="text-muted">{{ $ticket->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $ticket->category }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->priority_badge }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->status_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($ticket->assignedAdmin)
                                            <small>{{ $ticket->assignedAdmin->name }}</small>
                                        @else
                                            <small class="text-muted">Unassigned</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $ticket->created_at->format('d M Y') }}</small><br>
                                        <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.support.show', $ticket->ticket_number) }}"
                                           class="btn btn-sm btn-primary" title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Bulk Actions Toggle
function toggleBulkActions() {
    document.getElementById('bulkActionsBar').style.display = 'block';
}

function cancelBulkActions() {
    document.getElementById('bulkActionsBar').style.display = 'none';
    document.querySelectorAll('.ticket-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateSelectedCount();
}

// Select All Checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.ticket-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateSelectedCount();
});

// Individual Checkboxes
document.querySelectorAll('.ticket-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

// Update Selected Count
function updateSelectedCount() {
    const selected = document.querySelectorAll('.ticket-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = selected;
}

// Bulk Action Selection
document.getElementById('bulkAction').addEventListener('change', function() {
    const assignToSelect = document.getElementById('bulkAssignTo');
    const statusSelect = document.getElementById('bulkStatus');

    assignToSelect.style.display = 'none';
    statusSelect.style.display = 'none';

    if (this.value === 'assign') {
        assignToSelect.style.display = 'inline-block';
    } else if (this.value === 'status') {
        statusSelect.style.display = 'inline-block';
    }
});

// Bulk Form Submission
document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
    const selectedTickets = document.querySelectorAll('.ticket-checkbox:checked');
    if (selectedTickets.length === 0) {
        e.preventDefault();
        alert('Please select at least one ticket');
        return;
    }

    const action = document.getElementById('bulkAction').value;
    if (!action) {
        e.preventDefault();
        alert('Please select an action');
        return;
    }

    if (action === 'delete') {
        if (!confirm('Are you sure you want to delete the selected tickets? This action cannot be undone.')) {
            e.preventDefault();
            return;
        }
    }

    // Add selected ticket IDs to form
    selectedTickets.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ticket_ids[]';
        input.value = cb.value;
        this.appendChild(input);
    });
});
</script>

<style>
.border-left-primary { border-left: 4px solid #4e73df; }
.border-left-success { border-left: 4px solid #1cc88a; }
.border-left-info { border-left: 4px solid #36b9cc; }
.border-left-warning { border-left: 4px solid #f6c23e; }
.border-left-danger { border-left: 4px solid #e74a3b; }
.border-left-secondary { border-left: 4px solid #858796; }
</style>
@endsection
