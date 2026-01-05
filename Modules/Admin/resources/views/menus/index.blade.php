@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">{{ ucfirst($type) }} Menus</h4>
                    <div>
                        <a href="{{ route('admin.menus.index', ['type' => 'main']) }}" class="btn btn-{{ $type == 'main' ? 'dark' : 'outline-dark' }} waves-effect waves-light me-1">Main Menu</a>
                        <a href="{{ route('admin.menus.index', ['type' => 'footer']) }}" class="btn btn-{{ $type == 'footer' ? 'dark' : 'outline-dark' }} waves-effect waves-light me-1">Footer Menu</a>
                        <a href="{{ route('admin.menus.create', ['type' => $type]) }}" class="btn btn-primary waves-effect waves-light">
                            <i class="mdi mdi-plus-circle me-1"></i> Create Menu
                        </a>
                    </div>
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
                                <th>Name</th>
                                <th>URL</th>
                                <th>Parent</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $menu)
                                <tr class="table-primary">
                                    <td class="fw-bold">{{ $menu->name }}</td>
                                    <td>{{ $menu->url }}</td>
                                    <td>-</td>
                                    <td>{{ $menu->sort_order }}</td>
                                    <td>
                                        @if($menu->status)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-pencil"></i></a>
                                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-trash-can"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @foreach($menu->children as $child)
                                    <tr>
                                        <td class="ps-4"><i class="mdi mdi-subdirectory-arrow-right me-2"></i> {{ $child->name }}</td>
                                        <td>{{ $child->url }}</td>
                                        <td>{{ $menu->name }}</td>
                                        <td>{{ $child->sort_order }}</td>
                                        <td>
                                            @if($child->status)
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.menus.edit', $child->id) }}" class="btn btn-sm btn-info text-white"><i class="mdi mdi-pencil"></i></a>
                                                <form action="{{ route('admin.menus.destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-trash-can"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No menus found.</td>
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
