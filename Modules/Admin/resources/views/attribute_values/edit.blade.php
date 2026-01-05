@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Attribute Value</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.attribute_values.update', $attributeValue->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                   
                    <div class="mb-3">
                        <label for="attribute_id" class="form-label">Attribute</label>
                        <select class="form-select" id="attribute_id" name="attribute_id" required>
                            <option value="">Select Attribute</option>
                            @foreach($attributes as $attribute)
                                <option value="{{ $attribute->id }}" {{ old('attribute_id', $attributeValue->attribute_id) == $attribute->id ? 'selected' : '' }}>{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                         @error('attribute_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <input type="text" class="form-control" id="value" name="value" value="{{ old('value', $attributeValue->value) }}" required>
                         @error('value')
                             <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Value</button>
                        <a href="{{ route('admin.attribute_values.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
