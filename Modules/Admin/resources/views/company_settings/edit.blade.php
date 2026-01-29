@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Company Settings</h4>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.company_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <label for="company_name" class="col-sm-2 col-form-label">Company Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="company_name" name="company_name" value="{{ old('company_name', $setting->company_name) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="logo" class="col-sm-2 col-form-label">Logo</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="file" id="logo" name="logo">
                            @if($setting->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('uploads/'.$setting->logo) }}" alt="Current Logo" style="max-height: 100px;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $setting->address) }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $setting->phone) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $setting->email) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="whatsapp_no" class="col-sm-2 col-form-label">Whatsapp No</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="whatsapp_no" name="whatsapp_no" value="{{ old('whatsapp_no', $setting->whatsapp_no) }}">
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Social Media Links</h5>
                    <div class="row mb-3">
                        <label for="facebook" class="col-sm-2 col-form-label">Facebook</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="url" id="facebook" name="social_links[facebook]" value="{{ old('social_links.facebook', $setting->social_links['facebook'] ?? '') }}" placeholder="https://facebook.com/yourpage">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="twitter" class="col-sm-2 col-form-label">Twitter</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="url" id="twitter" name="social_links[twitter]" value="{{ old('social_links.twitter', $setting->social_links['twitter'] ?? '') }}" placeholder="https://twitter.com/yourhandle">
                        </div>
                    </div>

                     <div class="row mb-3">
                        <label for="instagram" class="col-sm-2 col-form-label">Instagram</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="url" id="instagram" name="social_links[instagram]" value="{{ old('social_links.instagram', $setting->social_links['instagram'] ?? '') }}" placeholder="https://instagram.com/yourhandle">
                        </div>
                    </div>
                     <div class="row mb-3">
                        <label for="linkedin" class="col-sm-2 col-form-label">LinkedIn</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="url" id="linkedin" name="social_links[linkedin]" value="{{ old('social_links.linkedin', $setting->social_links['linkedin'] ?? '') }}" placeholder="https://linkedin.com/in/yourprofile">
                        </div>
                    </div>


                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
