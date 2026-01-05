@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Change Password</h4>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('admin.password.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group auth-pass-inputgroup">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            <button class="btn btn-light password-addon" type="button"><i class="mdi mdi-eye-outline"></i></button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="input-group auth-pass-inputgroup">
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                            <button class="btn btn-light password-addon" type="button"><i class="mdi mdi-eye-outline"></i></button>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group auth-pass-inputgroup">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            <button class="btn btn-light password-addon" type="button"><i class="mdi mdi-eye-outline"></i></button>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary w-md">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.querySelectorAll('.password-addon').forEach(function(button) {
        button.addEventListener('click', function() {
            var input = this.previousElementSibling;
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        });
    });
</script>
@endpush
@endsection
