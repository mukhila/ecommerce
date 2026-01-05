@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit {{ ucfirst($menu->type) }} Menu</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $menu->name) }}" placeholder="Enter menu name" required>
                    </div>

                    <div class="mb-3">
                        <label for="url" class="form-label">URL / Route</label>
                        <input type="text" class="form-control" id="url" name="url" value="{{ old('url', $menu->url) }}" placeholder="e.g., /about-us or http://example.com">
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Menu</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">None (Top Level)</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $menu->sort_order) }}" placeholder="0">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status', $menu->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Menu</button>
                        <a href="{{ route('admin.menus.index', ['type' => $menu->type]) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
