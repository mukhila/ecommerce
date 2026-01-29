@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h4 class="card-title">Customers List</h4>
                    <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search customers..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Orders</th>
                                <th>Registered At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>#{{ $customer->id }}</td>
                                <td>
                                    <h6 class="mb-0">{{ $customer->name }}</h6>
                                </td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $customer->orders_count }} Orders</span>
                                </td>
                                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-eye"></i> View</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No customers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                   {{ $customers->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
