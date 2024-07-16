@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Test for {{ $test->topic->name }}</h1>
    <ul class="list-group">
        @foreach ($test->questions as $question)
            <li class="list-group-item">
                <strong>Question:</strong> {{ $question->question }}
                <br>
                <strong>Options:</strong> {{ implode(', ', $question->options) }}
                <br>
                <strong>Correct Answers:</strong> {{ implode(', ', $question->correct_answers) }}
            </li>
        @endforeach
    </ul>
    <a href="{{ route('tests.edit', $test) }}" class="btn btn-secondary mt-3">Edit</a>
    <form action="{{ route('tests.destroy', $test) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mt-3">Delete</button>
    </form>
</div>
@endsection
