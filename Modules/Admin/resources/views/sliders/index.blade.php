@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">Home Page Sliders</h4>
                    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="mdi mdi-plus-circle me-1"></i> Create Slider
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
                                <th>Sort Order</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Subtitle</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sliders as $slider)
                            <tr>
                                <td>{{ $slider->sort_order }}</td>
                                <td>
                                    <img src="{{ asset($slider->image) }}" alt="Slider Image" width="100" class="img-thumbnail">
                                </td>
                                <td>{{ $slider->title }}</td>
                                <td>{{ $slider->subtitle }}</td>
                                <td>
                                    @if($slider->status)
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-pencil"></i></a>
                                        <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this slider?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No sliders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
