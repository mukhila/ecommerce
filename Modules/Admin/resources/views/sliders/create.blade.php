@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create Slider</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="image" class="form-label">Image (Required)</label>
                        <input type="file" class="form-control" id="image" name="image" required accept="image/*">
                        <div class="form-text">Recommended size: 1920x600px</div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Enter title">
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle') }}" placeholder="Enter subtitle">
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Link (URL)</label>
                        <input type="text" class="form-control" id="link" name="link" value="{{ old('link') }}" placeholder="e.g., http://example.com/category">
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Slider</button>
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
