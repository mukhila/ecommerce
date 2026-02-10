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
                                                <img src="{{ asset('uploads/'.$image->image_path) }}" class="img-fluid rounded {{ $image->is_primary ? 'border border-primary border-3' : '' }}" alt="Product Image">
                                                <a href="{{ route('admin.products.image.destroy', $image->id) }}" onclick="return confirm('Delete this image?')" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" style="padding: 0px 5px;">&times;</a>
                                                <div class="text-center mt-1">
                                                    @if($image->is_primary)
                                                        <span class="badge bg-primary">Primary</span>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-outline-primary set-primary-btn" style="font-size: 11px;" data-url="{{ route('admin.products.image.setPrimary', $image->id) }}">Set as Primary</button>
                                                    @endif
                                                </div>
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

                                        document.querySelectorAll('.set-primary-btn').forEach(function(btn) {
                                            btn.addEventListener('click', function() {
                                                var token = document.querySelector('input[name="_token"]').value;
                                                fetch(this.dataset.url, {
                                                    method: 'PATCH',
                                                    headers: {
                                                        'X-CSRF-TOKEN': token,
                                                        'Accept': 'application/json'
                                                    }
                                                }).then(function() {
                                                    window.location.reload();
                                                });
                                            });
                                        });
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
                                            <label class="form-label fw-bold">{{ $attribute->name }}</label>
                                            @if($attribute->slug === 'size' || $attribute->name === 'Size')
                                                {{-- Size Management Table --}}
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm mb-2" id="size-table">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th style="width: 50px;">Enable</th>
                                                                <th style="width: 80px;">Size</th>
                                                                <th style="width: 120px;">Stock</th>
                                                                <th style="width: 140px;">Price Override</th>
                                                                <th style="width: 80px;">Status</th>
                                                                <th style="width: 100px;">In Orders</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($attribute->values as $value)
                                                                @php
                                                                    $isSelected = isset($productAttributes[$attribute->id][$value->id]);
                                                                    $details = $isSelected ? $productAttributes[$attribute->id][$value->id] : [];
                                                                    $stock = $details['stock'] ?? 0;
                                                                    $hasOrders = $isSelected && \App\Models\OrderItem::where('variation_id', $details['id'] ?? 0)->exists();
                                                                @endphp
                                                                <tr class="{{ $isSelected && $stock == 0 ? 'table-warning' : '' }}">
                                                                    <td class="text-center align-middle">
                                                                        <input class="form-check-input size-enable-checkbox"
                                                                               type="checkbox"
                                                                               name="attributes[{{ $attribute->id }}][{{ $value->id }}][enabled]"
                                                                               value="1"
                                                                               id="attr_val_{{ $value->id }}"
                                                                               data-size="{{ $value->value }}"
                                                                               {{ $isSelected ? 'checked' : '' }}
                                                                               {{ $hasOrders ? 'data-has-orders=1' : '' }}>
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <strong>{{ $value->value }}</strong>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number"
                                                                               name="attributes[{{ $attribute->id }}][{{ $value->id }}][stock]"
                                                                               class="form-control form-control-sm stock-input"
                                                                               placeholder="0"
                                                                               min="0"
                                                                               value="{{ $details['stock'] ?? '' }}"
                                                                               {{ !$isSelected ? 'disabled' : '' }}>
                                                                    </td>
                                                                    <td>
                                                                        <div class="input-group input-group-sm">
                                                                            <span class="input-group-text">₹</span>
                                                                            <input type="number"
                                                                                   step="0.01"
                                                                                   min="0"
                                                                                   name="attributes[{{ $attribute->id }}][{{ $value->id }}][price]"
                                                                                   class="form-control price-input"
                                                                                   placeholder="Use base"
                                                                                   value="{{ $details['price'] ?? '' }}"
                                                                                   {{ !$isSelected ? 'disabled' : '' }}>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center align-middle">
                                                                        @if($isSelected)
                                                                            @if($stock > 10)
                                                                                <span class="badge bg-success">In Stock</span>
                                                                            @elseif($stock > 0)
                                                                                <span class="badge bg-warning text-dark">Low ({{ $stock }})</span>
                                                                            @else
                                                                                <span class="badge bg-danger">Out</span>
                                                                            @endif
                                                                        @else
                                                                            <span class="badge bg-secondary">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center align-middle">
                                                                        @if($hasOrders)
                                                                            <span class="badge bg-info" title="This size has been ordered">
                                                                                <i class="bi bi-lock-fill"></i> Yes
                                                                            </span>
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-info-circle"></i>
                                                        Leave price empty to use the base product price. Sizes with orders cannot be fully removed.
                                                    </small>
                                                    <div>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="select-all-sizes">
                                                            Select All
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="deselect-all-sizes">
                                                            Deselect All
                                                        </button>
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        // Toggle input fields based on checkbox
                                                        document.querySelectorAll('.size-enable-checkbox').forEach(function(checkbox) {
                                                            checkbox.addEventListener('change', function() {
                                                                const row = this.closest('tr');
                                                                const stockInput = row.querySelector('.stock-input');
                                                                const priceInput = row.querySelector('.price-input');

                                                                // Prevent disabling if has orders
                                                                if (!this.checked && this.dataset.hasOrders) {
                                                                    this.checked = true;
                                                                    alert('This size has been used in orders and cannot be disabled. You can set stock to 0 instead.');
                                                                    return;
                                                                }

                                                                stockInput.disabled = !this.checked;
                                                                priceInput.disabled = !this.checked;

                                                                if (!this.checked) {
                                                                    stockInput.value = '';
                                                                    priceInput.value = '';
                                                                }
                                                            });
                                                        });

                                                        // Select/Deselect all
                                                        document.getElementById('select-all-sizes')?.addEventListener('click', function() {
                                                            document.querySelectorAll('.size-enable-checkbox').forEach(function(cb) {
                                                                if (!cb.checked) {
                                                                    cb.checked = true;
                                                                    cb.dispatchEvent(new Event('change'));
                                                                }
                                                            });
                                                        });

                                                        document.getElementById('deselect-all-sizes')?.addEventListener('click', function() {
                                                            document.querySelectorAll('.size-enable-checkbox').forEach(function(cb) {
                                                                if (cb.checked && !cb.dataset.hasOrders) {
                                                                    cb.checked = false;
                                                                    cb.dispatchEvent(new Event('change'));
                                                                }
                                                            });
                                                        });

                                                        // Validate stock >= 0
                                                        document.querySelectorAll('.stock-input').forEach(function(input) {
                                                            input.addEventListener('change', function() {
                                                                if (this.value !== '' && parseInt(this.value) < 0) {
                                                                    this.value = 0;
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <select class="form-select" id="attr_{{ $attribute->id }}" name="attribute_values[{{ $attribute->id }}]">
                                                    <option value="">Select {{ $attribute->name }}</option>
                                                    @foreach($attribute->values as $value)
                                                        <option value="{{ $value->id }}"
                                                            {{ isset($productAttributes[$attribute->id][$value->id]) ? 'selected' : '' }}
                                                        >
                                                            {{ $value->value }}
                                                        </option>
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
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
