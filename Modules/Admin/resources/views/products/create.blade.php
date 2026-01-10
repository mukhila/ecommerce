@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create Product</h4>

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

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Basic Information</h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                                    </div>
                                    
                                     <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="price" class="form-label">Price (₹)</label>
                                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                                            <small class="text-muted">Price excluding GST</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sale_price" class="form-label">Sale Price (₹)</label>
                                            <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                            <small class="text-muted">Optional discounted price</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="gst_percentage" class="form-label">GST Rate (%)</label>
                                            <select class="form-select" id="gst_percentage" name="gst_percentage" required>
                                                <option value="5" {{ old('gst_percentage') == 5 ? 'selected' : '' }}>5%</option>
                                                <option value="12" {{ old('gst_percentage') == 12 ? 'selected' : '' }}>12%</option>
                                                <option value="18" {{ old('gst_percentage', 18) == 18 ? 'selected' : '' }}>18%</option>
                                                <option value="28" {{ old('gst_percentage') == 28 ? 'selected' : '' }}>28%</option>
                                            </select>
                                            <small class="text-muted">GST will be added to the price</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fabric_type" class="form-label">Fabric Type</label>
                                            <select class="form-select" id="fabric_type" name="fabric_type">
                                                <option value="">Not a fabric product</option>
                                                <option value="Cotton" {{ old('fabric_type') == 'Cotton' ? 'selected' : '' }}>Cotton</option>
                                                <option value="Silk" {{ old('fabric_type') == 'Silk' ? 'selected' : '' }}>Silk</option>
                                                <option value="Wool" {{ old('fabric_type') == 'Wool' ? 'selected' : '' }}>Wool</option>
                                                <option value="Linen" {{ old('fabric_type') == 'Linen' ? 'selected' : '' }}>Linen</option>
                                                <option value="Polyester" {{ old('fabric_type') == 'Polyester' ? 'selected' : '' }}>Polyester</option>
                                                <option value="Denim" {{ old('fabric_type') == 'Denim' ? 'selected' : '' }}>Denim</option>
                                                <option value="Chiffon" {{ old('fabric_type') == 'Chiffon' ? 'selected' : '' }}>Chiffon</option>
                                                <option value="Velvet" {{ old('fabric_type') == 'Velvet' ? 'selected' : '' }}>Velvet</option>
                                                <option value="Synthetic" {{ old('fabric_type') == 'Synthetic' ? 'selected' : '' }}>Synthetic</option>
                                            </select>
                                            <small class="text-muted">Optional: for fabric products</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="stock" class="form-label">Stock Quantity</label>
                                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Images</h5>
                                    <div class="mb-3">
                                        <label for="images" class="form-label">Product Images</label>
                                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(this)">
                                        <div class="form-text">You can select multiple images.</div>
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
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                    
                                     <div class="mb-3 form-check">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Featured Product</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Attributes</h5>
                                    @foreach($attributes as $attribute)
                                        <div class="mb-3">
                                            <label class="form-label">{{ $attribute->name }}</label>
                                            @if($attribute->name === 'Size')
                                                <div class="border p-2 rounded" style="max-height: 300px; overflow-y: auto;">
                                                    @foreach($attribute->values as $value)
                                                        <div class="d-flex align-items-center mb-2 gap-2">
                                                            <div class="form-check" style="min-width: 100px;">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="attributes[{{ $attribute->id }}][{{ $value->id }}][enabled]" 
                                                                       value="1" 
                                                                       id="attr_val_{{ $value->id }}"
                                                                       {{ old('attributes.'.$attribute->id.'.'.$value->id.'.enabled') ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="attr_val_{{ $value->id }}">
                                                                    {{ $value->value }}
                                                                </label>
                                                            </div>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">Qty</span>
                                                                <input type="number" 
                                                                       name="attributes[{{ $attribute->id }}][{{ $value->id }}][stock]" 
                                                                       class="form-control" 
                                                                       placeholder="Stock"
                                                                       value="{{ old('attributes.'.$attribute->id.'.'.$value->id.'.stock') }}">
                                                            </div>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">₹</span>
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       name="attributes[{{ $attribute->id }}][{{ $value->id }}][price]" 
                                                                       class="form-control" 
                                                                       placeholder="Price Override"
                                                                       value="{{ old('attributes.'.$attribute->id.'.'.$value->id.'.price') }}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <small class="text-muted">Select available sizes and optional specific stock/price.</small>
                                            @else
                                                <select class="form-select" id="attr_{{ $attribute->id }}" name="attribute_values[{{ $attribute->id }}]">
                                                    <option value="">Select {{ $attribute->name }}</option>
                                                    @foreach($attribute->values as $value)
                                                        <option value="{{ $value->id }}" {{ old('attribute_values.'.$attribute->id) == $value->id ? 'selected' : '' }}>{{ $value->value }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Create Product</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
