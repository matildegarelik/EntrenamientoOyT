@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Topics</h1>
    <a href="{{ route('topics.create') }}" class="btn btn-primary mb-3">Create Topic</a>
    <ul class="list-group">
        @foreach ($parent_topics as $topic)
            <li class="list-group-item">
                <a href="{{ route('topics.show', $topic) }}">{{ $topic->name }}</a>
                <a href="{{ route('topics.children', $topic) }}" class="btn btn-secondary btn-sm float-right ml-2">View Children</a>
                <a href="{{ route('topics.create', ['parent_id' => $topic->id]) }}" class="btn btn-secondary btn-sm float-right ml-2">Add Child</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
