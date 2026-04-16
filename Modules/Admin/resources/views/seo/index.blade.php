@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 card-no-border">
                <div class="d-flex justify-content-between">
                    <h4>SEO Management</h4>
                    <a href="{{ route('admin.seo.create') }}" class="btn btn-primary">Add SEO</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Route Name</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seos as $seo)
                            <tr>
                                <td>{{ $seo->route_name }}</td>
                                <td>{{ $seo->title }}</td>
                                <td>{{ $seo->type }}</td>
                                <td>
                                    <a href="{{ route('admin.seo.edit', $seo) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.seo.destroy', $seo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
