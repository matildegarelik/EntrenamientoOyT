@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tests for {{ $topic->name }}</h1>
    @foreach ($topic->test->questions as $question)
        <div class="form-group">
            <label>{{ $question->question }}</label>
            <div>
                @foreach ($question->options as $option)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="{{ $option }}">
                        <label class="form-check-label">{{ $option }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
@endsection
