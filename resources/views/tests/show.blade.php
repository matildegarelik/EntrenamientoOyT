@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Test for {{ $test->topic->name }}</h1>
    <strong>Amount of questions to show:</strong> {{$test->amount_questions}}
    <br>
    <ul class="list-group">
        @foreach ($test->questions as $question)
            <li class="list-group-item">
                <strong>Question:</strong> {{ $question->question }}
                <br>
                <strong>Type:</strong> {{$question->type}}
                <br>
                <strong>Options:</strong> <br>
                @foreach ($question->options as $option)
                    <input type="radio" disabled>{{$option}}<br>
                @endforeach
                <br>
                <strong>Correct Answers:</strong> 
                @foreach($question->correct_answers as $i=> $ca)
                    @if($ca==1)
                        {{$question->options[$i]}}
                    @endif
                @endforeach
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
