@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Test</h1>
    <form id="test-form" action="{{ route('tests.update', $test) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="topic_name">Topic</label>
            <input type="text" class="form-control" id="topic_name" value="{{ $test->topic->name }}" readonly>
            <input type="hidden" name="topic_id" value="{{ $test->topic_id }}">
        </div>

        <div id="questions-container">
            @foreach ($test->questions as $index => $question)
            <div class="question-template">
                <div class="form-group">
                    <label for="question_{{ $index }}">Question</label>
                    <input type="text" class="form-control question" id="question_{{ $index }}" name="questions[{{ $index }}][question]" value="{{ $question->question }}" required>
                </div>
                <div class="options-container">
                    @foreach ($question->options as $i => $option)
                    <div class="form-group option">
                        <label for="options_{{ $index }}_{{ $i }}">Option {{ $i + 1 }}</label>
                        <input type="text" class="form-control options" id="options_{{ $index }}_{{ $i }}" name="questions[{{ $index }}][options][{{ $i }}]" value="{{ $option }}" required>
                        <label>
                            <input type="checkbox" class="correct-answer-chk" {{ $question->correct_answers[$i]=='1' ? 'checked' : '' }}>
                            Correct Answer
                        </label>
                        <input type="hidden" class="correct-answer-hidden" name="questions[{{ $index }}][correct_answers][{{ $i }}]" value="{{$question->correct_answers[$i]}}">
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-danger remove-question">Remove Question</button>
                <hr>
            </div>
            @endforeach

            <div class="question-template t0" style="display: none;">
                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" class="form-control question">
                </div>
                <div class="options-container">
                    @for ($i = 0; $i < 4; $i++)
                    <div class="form-group option">
                        <label for="options">Option {{ $i + 1 }}</label>
                        <input type="text" class="form-control options">
                        <label>
                            <input type="checkbox" class="correct-answer-chk">
                            Correct Answer
                        </label>
                        <input type="hidden" class="correct-answer-hidden" value="0">
                    </div>
                    @endfor
                </div>
                <button type="button" class="btn btn-danger remove-question">Remove Question</button>
                <hr>
            </div>
        </div>

        <button type="button" id="add-question" class="btn btn-secondary">Add Question</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('add-question').addEventListener('click', function() {
    const container = document.getElementById('questions-container');
    const template = document.querySelector('.question-template.t0').cloneNode(true);
    template.style.display = 'block';
    template.classList.remove('t0');
    template.querySelectorAll('input').forEach(input => input.value = '');
    const questionCount = container.querySelectorAll('.question-template').length;

    template.querySelector('.question').name = `questions[${questionCount}][question]`;
    template.querySelectorAll('.options').forEach((input, index) => {
        input.name = `questions[${questionCount}][options][${index}]`;
    });
    template.querySelectorAll('.correct-answer-chk').forEach((input, index) => {
        input.id = `questions[${questionCount}][correct_answers][${index}]`;
        input.checked = false;
    });
    template.querySelectorAll('.correct-answer-hidden').forEach((input, index) => {
        input.name = `questions[${questionCount}][correct_answers][${index}]`;
        input.value = '0';
    });

    container.appendChild(template);
});

document.getElementById('questions-container').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-question')) {
        event.target.closest('.question-template').remove();
    }
});

document.addEventListener('change', function(event) {
    if (event.target.classList.contains('correct-answer-chk')) {
        const questionTemplate = event.target.closest('.question-template');
        const checkboxes = questionTemplate.querySelectorAll('.correct-answer-chk');
        checkboxes.forEach(checkbox => {
            if (checkbox !== event.target) {
                checkbox.checked = false;
                checkbox.closest('.option').querySelector('.correct-answer-hidden').value = '0';
            }
        });

        const hiddenInput = event.target.closest('.option').querySelector('.correct-answer-hidden');
        hiddenInput.value = event.target.checked ? '1' : '0';
    }
});

document.getElementById('test-form').addEventListener('submit', function(event) {
    const questions = document.querySelectorAll('.question-template:not(.t0)');
    let isValid = true;

    questions.forEach(function(question) {
        const correctAnswers = question.querySelectorAll('.correct-answer-chk:checked');
        if (correctAnswers.length === 0) {
            isValid = false;
            question.querySelector('.form-group').classList.add('has-error');
        } else {
            question.querySelector('.form-group').classList.remove('has-error');
        }
    });

    if (!isValid) {
        event.preventDefault();
        alert('Please ensure that each question has at least one correct answer.');
    }
});
</script>
@endsection
