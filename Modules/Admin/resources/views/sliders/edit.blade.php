@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Slider</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Leave blank to keep current image.</div>
                        @if($slider->image)
                            <div class="mt-2">
                                <img src="{{ asset($slider->image) }}" alt="Current Image" width="150" class="img-thumbnail">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $slider->title) }}" placeholder="Enter title">
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" placeholder="Enter subtitle">
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Link (URL)</label>
                        <input type="text" class="form-control" id="link" name="link" value="{{ old('link', $slider->link) }}" placeholder="e.g., http://example.com/category">
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}" placeholder="0">
                    </div>

                    <div class="mb-3 form-check">
                         <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $slider->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Slider</button>
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
