@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Tests</h2>
    @foreach($userTests as $userTest)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $userTest->test->topic->name }} - Test Result</h5>
                <p>Score: {{$userTest->score*100/count($userTest->test->questions)}}% ({{ $userTest->score }}/{{ count($userTest->test->questions) }})</p>
                @foreach($userTest->answers as $answerData)
                    <div>
                        <strong>Question:</strong> {{ $answerData['question'] }}<br>
                        @if(is_array($answerData['user_answer']))
                            <strong>Your Answers:</strong>
                            @foreach($answerData['user_answer'] as $ua)
                                {{ $answerData['options'][$ua] ?? 'Not answered' }}
                                @if (!$loop->last), @endif
                            @endforeach
                            <br>
                        @else
                            <strong>Your Answer:</strong> {{ $answerData['options'][$answerData['user_answer']] ?? 'Not answered' }}<br>
                        @endif
                        <strong>Correct Answers:</strong>
                        @foreach($answerData['options'] as $index => $option)
                            @if($answerData['correct_answers'][$index] == 1)
                                {{ $option }}@if (!$loop->last), @endif
                            @endif
                        @endforeach
                        <br>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
