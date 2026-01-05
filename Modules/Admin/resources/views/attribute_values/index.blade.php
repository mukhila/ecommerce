@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">Attribute Values</h4>
                    <a href="{{ route('admin.attribute_values.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="mdi mdi-plus-circle me-1"></i> Create Value
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Attribute</th>
                                <th>Value</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attributeValues as $value)
                            <tr>
                                <td>{{ $value->id }}</td>
                                <td>{{ $value->attribute->name }}</td>
                                <td>{{ $value->value }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.attribute_values.edit', $value->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('admin.attribute_values.destroy', $value->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this value?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No attribute values found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                   {{ $attributeValues->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
