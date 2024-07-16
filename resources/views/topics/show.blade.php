@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $topic->name }}</h1>

    <div class="content">
        {!! $topic->content !!}
    </div>

    @if ($topic->children->count() > 0)
        <h3>Children Topics:</h3>
        <ul class="list-group">
            @foreach ($topic->children as $child)
                <li class="list-group-item">
                    <a href="{{ route('topics.show', $child) }}">{{ $child->name }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No child topics available.</p>
    @endif

    @can('update', $topic)
        <a href="{{ route('topics.edit', $topic) }}" class="btn btn-primary">Edit</a>
    @endcan

    @can('delete', $topic)
        <form action="{{ route('topics.destroy', $topic) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    @endcan
</div>
@endsection
