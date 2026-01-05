@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Product: {{ $product->name }}</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                 @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                     <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Basic Information</h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                    
                                     <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="price" class="form-label">Price (₹)</label>
                                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                            <small class="text-muted">Price excluding GST</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sale_price" class="form-label">Sale Price (₹)</label>
                                            <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                                            <small class="text-muted">Optional discounted price</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="gst_percentage" class="form-label">GST Rate (%)</label>
                                            <select class="form-select" id="gst_percentage" name="gst_percentage" required>
                                                <option value="5" {{ old('gst_percentage', $product->gst_percentage) == 5 ? 'selected' : '' }}>5%</option>
                                                <option value="12" {{ old('gst_percentage', $product->gst_percentage) == 12 ? 'selected' : '' }}>12%</option>
                                                <option value="18" {{ old('gst_percentage', $product->gst_percentage) == 18 ? 'selected' : '' }}>18%</option>
                                                <option value="28" {{ old('gst_percentage', $product->gst_percentage) == 28 ? 'selected' : '' }}>28%</option>
                                            </select>
                                            <small class="text-muted">GST will be added to the price</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fabric_type" class="form-label">Fabric Type</label>
                                            <select class="form-select" id="fabric_type" name="fabric_type">
                                                <option value="" {{ empty($product->fabric_type) ? 'selected' : '' }}>Not a fabric product</option>
                                                <option value="Cotton" {{ old('fabric_type', $product->fabric_type) == 'Cotton' ? 'selected' : '' }}>Cotton</option>
                                                <option value="Silk" {{ old('fabric_type', $product->fabric_type) == 'Silk' ? 'selected' : '' }}>Silk</option>
                                                <option value="Wool" {{ old('fabric_type', $product->fabric_type) == 'Wool' ? 'selected' : '' }}>Wool</option>
                                                <option value="Linen" {{ old('fabric_type', $product->fabric_type) == 'Linen' ? 'selected' : '' }}>Linen</option>
                                                <option value="Polyester" {{ old('fabric_type', $product->fabric_type) == 'Polyester' ? 'selected' : '' }}>Polyester</option>
                                                <option value="Denim" {{ old('fabric_type', $product->fabric_type) == 'Denim' ? 'selected' : '' }}>Denim</option>
                                                <option value="Chiffon" {{ old('fabric_type', $product->fabric_type) == 'Chiffon' ? 'selected' : '' }}>Chiffon</option>
                                                <option value="Velvet" {{ old('fabric_type', $product->fabric_type) == 'Velvet' ? 'selected' : '' }}>Velvet</option>
                                                <option value="Synthetic" {{ old('fabric_type', $product->fabric_type) == 'Synthetic' ? 'selected' : '' }}>Synthetic</option>
                                            </select>
                                            <small class="text-muted">Optional: for fabric products</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="stock" class="form-label">Stock Quantity</label>
                                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Images</h5>
                                    <div class="row mb-3">
                                        @foreach($product->images as $image)
                                            <div class="col-md-3 mb-3 position-relative">
                                                <img src="{{ Storage::url($image->image_path) }}" class="img-fluid rounded" alt="Product Image">
                                                <a href="{{ route('admin.products.image.destroy', $image->id) }}" onclick="return confirm('Delete this image?')" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" style="padding: 0px 5px;">&times;</a>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="images" class="form-label">Upload New Images</label>
                                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(this)">
                                         <div id="image-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                                    </div>
                                    
                                     <script>
                                        function previewImages(input) {
                                            var preview = document.getElementById('image-preview');
                                            preview.innerHTML = '';
                                            
                                            if (input.files) {
                                                [].forEach.call(input.files, function(file) {
                                                    var reader = new FileReader();
                                                    
                                                    reader.onload = function(e) {
                                                        var img = document.createElement('img');
                                                        img.src = e.target.result;
                                                        img.className = 'img-thumbnail';
                                                        img.style.maxWidth = '100px';
                                                        img.style.maxHeight = '100px';
                                                        preview.appendChild(img);
                                                    }
                                                    
                                                    reader.readAsDataURL(file);
                                                });
                                            }
                                        }
                                    </script>
                                </div>
                            </div>

                        </div>
                         <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Organization</h5>
                                     <div class="mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                    
                                     <div class="mb-3 form-check">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Featured Product</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Attributes</h5>
                                    @foreach($attributes as $attribute)
                                        <div class="mb-3">
                                            <label for="attr_{{ $attribute->id }}" class="form-label">{{ $attribute->name }}</label>
                                            <select class="form-select" id="attr_{{ $attribute->id }}" name="attribute_values[{{ $attribute->id }}]">
                                                <option value="">Select {{ $attribute->name }}</option>
                                                @foreach($attribute->values as $value)
                                                    <option value="{{ $value->id }}" 
                                                        {{ (old('attribute_values.'.$attribute->id) == $value->id) || (isset($productAttributes[$attribute->id]) && $productAttributes[$attribute->id] == $value->id) ? 'selected' : '' }}
                                                    >
                                                        {{ $value->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
