@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Test</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="test-form" method="POST" action="{{ route('tests.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="topic_id">Topic</label>
            <select name="topic_id" id="topic_id" class="form-control">
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Amount of questions to show</label>
            <input type="number" name="amount_questions" class="form-control" value="10"></input>
        </div>

        <div id="questions-container">
            <div class="question-template t0" style="display: none;">
                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" class="form-control question">
                </div>
                <div class="form-group">
                    <label for="image">Image (optional)</label>
                    <input type="file" class="form-control image" accept="image/*">
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
        <button type="button" id="add-question" class="btn btn-primary">Add Question</button>
        <button type="submit" class="btn btn-success">Create Test</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('add-question').addEventListener('click', function() {
        const container = document.getElementById('questions-container');
        const template = document.querySelector('.t0').cloneNode(true);
        template.style.display = 'block';
        template.classList.remove('t0');

        const questionCount = container.querySelectorAll('.question').length - 1;
        template.querySelector('.question').name = `questions[${questionCount}][question]`;
        template.querySelectorAll('.options').forEach((input, index) => {
            input.name = `questions[${questionCount}][options][${index}]`;
        });
        template.querySelectorAll('.correct-answer-chk').forEach((input, index) => {
            input.id = `questions[${questionCount}][correct_answers][${index}]`;
        });
        template.querySelectorAll('.correct-answer-hidden').forEach((input, index) => {
            input.name = `questions[${questionCount}][correct_answers][${index}]`;
        });
        template.querySelector('.image').name = `questions[${questionCount}][image]`;

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
