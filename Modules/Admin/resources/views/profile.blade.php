@extends('admin::layouts.main')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fw-bold mb-4">Profile</h4>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="{{ auth()->guard('admin')->user()->name }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ auth()->guard('admin')->user()->email }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" class="form-control" value="{{ auth()->guard('admin')->user()->mobile }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="{{ auth()->guard('admin')->user()->role }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Employee Code</label>
                        <input type="text" class="form-control" value="{{ auth()->guard('admin')->user()->employee_code }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control" value="{{ auth()->guard('admin')->user()->status }}" readonly>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
