@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 card-no-border">
                <h4>Edit SEO</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="theme-form mega-form" action="{{ route('admin.seo.update', $seo) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="col-form-label pt-0">Route Name</label>
                        <select class="form-select" name="route_name" required>
                            <option value="">Select Route</option>
                            @foreach($routes as $route)
                                <option value="{{ $route }}" {{ $seo->route_name == $route ? 'selected' : '' }}>{{ $route }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label pt-0">Title</label>
                        <input class="form-control" type="text" name="title" value="{{ old('title', $seo->title) }}">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label pt-0">Description</label>
                        <textarea class="form-control" name="description">{{ old('description', $seo->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label pt-0">Keywords</label>
                        <textarea class="form-control" name="keywords">{{ old('keywords', $seo->keywords) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label pt-0">Robots</label>
                        <input class="form-control" type="text" name="robots" value="{{ old('robots', $seo->robots) }}">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label pt-0">Canonical URL</label>
                        <input class="form-control" type="url" name="canonical_url" value="{{ old('canonical_url', $seo->canonical_url) }}">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label pt-0">Type</label>
                        <select class="form-select" name="type">
                            <option value="website" {{ old('type', $seo->type) == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="article" {{ old('type', $seo->type) == 'article' ? 'selected' : '' }}>Article</option>
                            <option value="product" {{ old('type', $seo->type) == 'product' ? 'selected' : '' }}>Product</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label pt-0">OG Image</label>
                        <input class="form-control" type="file" name="image" accept="image/*">
                        @if($seo->image)
                            <img src="{{ asset($seo->image) }}" alt="Current Image" width="100" class="mt-2">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Update SEO</button>
                    <a href="{{ route('admin.seo.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
