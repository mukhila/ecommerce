@extends('admin::layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Page</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" id="pageForm">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <label for="title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="content" class="col-sm-2 col-form-label">Content</label>
                        <div class="col-sm-10">
                            <div id="snow-editor" style="height: 300px;">
                                {!! old('content', $page->content) !!}
                            </div>
                            <input type="hidden" name="content" id="content">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                <input class="form-check-input" type="checkbox" id="status" name="status" {{ old('status', $page->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Update Page</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="{{ asset('adminassets/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('adminassets/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('adminassets/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
<script src="{{ asset('adminassets/libs/quill/quill.js') }}"></script>
<script>
    var quill = new Quill('#snow-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': [] }, { 'size': [] }],
                [ 'bold', 'italic', 'underline', 'strike' ],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'super' }, { 'script': 'sub' }],
                [{ 'header': [1, 2, 3, 4, 5, 6] }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered' }, { 'list': 'bullet'}, { 'indent': '-1' }, { 'indent': '+1' }],
                [ 'direction', { 'align': [] }],
                [ 'link', 'image', 'video', 'formula' ],
                [ 'clean' ]
            ]
        }
    });

    // Update hidden input on form submit
    var form = document.querySelector('#pageForm');
    form.onsubmit = function() {
        var content = document.querySelector('#content');
        content.value = quill.root.innerHTML;
    };
</script>
@endsection
