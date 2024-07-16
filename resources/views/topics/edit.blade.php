@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Topic</h1>
    <form method="POST" action="{{ route('topics.update', $topic) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $topic->name }}" required>
        </div>
        <div class="form-group">
            <label for="parent_id">Parent Topic</label>
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="">None</option>
                @foreach ($topics as $top)
                    <option value="{{ $top->id }}" {{ $topic->parent_id == $top->id ? 'selected' : '' }}>{{ $top->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="topic-content" name="content">{{ $topic->content }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    tinymce.init({
        selector: '#topic-content',
        plugins: 'advlist autolink lists link image charmap print preview anchor',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        toolbar_mode: 'floating',
        height: 400
    });
</script>
@endsection
