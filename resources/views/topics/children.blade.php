@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Children of {{ $topic->name }}</h1>
    <ul class="list-group">
        @foreach ($children as $child)
            <li class="list-group-item">
                <a href="{{ route('topics.show', $child) }}">{{ $child->name }}</a>
                <a href="{{ route('topics.children', $child) }}" class="btn btn-secondary btn-sm float-right ml-2">View Children</a>
                <a href="{{ route('topics.create', ['parent_id' => $child->id]) }}" class="btn btn-secondary btn-sm float-right ml-2">Add Child</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
