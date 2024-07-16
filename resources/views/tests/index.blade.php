@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tests</h1>
    <a href="{{ route('tests.create') }}" class="btn btn-primary mb-3">Create Test</a>
    <ul class="list-group">
        @foreach ($tests as $test)
            <li class="list-group-item">
                <a href="{{ route('tests.show', $test) }}">Test for {{ $test->topic->name }}</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
