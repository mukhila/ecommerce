@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create Coupon Code</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf
                   
                    <div class="mb-3">
                        <label for="code" class="form-label">Coupon Code</label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="e.g. ON25" required style="text-transform:uppercase">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Discount Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                             <label for="value" class="form-label">Discount Value</label>
                             <input type="number" step="0.01" class="form-control" id="value" name="value" value="{{ old('value') }}" placeholder="e.g. 15" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                             <label for="start_date" class="form-label">Start Date</label>
                             <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                             <label for="expiry_date" class="form-label">Expiry Date</label>
                             <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Coupon</button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
