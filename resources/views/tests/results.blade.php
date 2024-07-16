@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Test Results</h1>
    <h3>Topic: {{ $userTest->test->topic->name }}</h3>
    <h4>User: {{ $userTest->user->name }}</h4>
    <h4>Score: {{ $userTest->score }}%</h4>
    <ul class="list-group">
        @foreach ($userTest->test->questions as $question)
            <li class="list-group-item">
                <strong>Question:</strong> {{ $question->question }}
                <br>
                <strong>Your Answer:</strong> {{ $userTest->answers[$question->id] ?? 'Not Answered' }}
                <br>
                <strong>Correct Answers:</strong> {{ implode(', ', $question->correct_answers) }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
