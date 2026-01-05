@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">Coupon Codes</h4>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="mdi mdi-plus-circle me-1"></i> Create Coupon
                    </a>
                </div>

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
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td><span class="badge bg-primary font-size-12">{{ $coupon->code }}</span></td>
                                <td>
                                    {{ $coupon->value }} 
                                    @if($coupon->type == 'percent')
                                        % Off
                                    @else
                                        Fixed Off
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->start_date)
                                        {{ \Carbon\Carbon::parse($coupon->start_date)->format('d M, Y') }}
                                    @else
                                        - 
                                    @endif
                                    
                                    to 

                                    @if($coupon->expiry_date)
                                         {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d M, Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->status)
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No coupons found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                   {{ $coupons->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
