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
    <form method="POST" action="{{ route('tests.store') }}">
        @csrf
        <div class="form-group">
            <label for="topic_id">Topic</label>
            <select name="topic_id" id="topic_id" class="form-control">
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="questions-container">
            <div class="question-template t0" style="display: none;">
                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" class="form-control question">
                </div>
                <div class="options-container">
                    <div class="form-group option">
                        <label for="options">Option</label>
                        <input type="text" class="form-control options">
                        <label>
                            <input type="checkbox" class="correct-answer-chk">
                            Correct Answer
                        </label>
                        <input type="hidden" class="correct-answer-hidden" value="0">
                    </div>
                </div>
                <button type="button" class="btn btn-secondary add-option">Add Option</button>
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

        container.appendChild(template);
    });

    document.getElementById('questions-container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-question')) {
            event.target.closest('.question-template').remove();
        } else if (event.target.classList.contains('add-option')) {
            const questionTemplate = event.target.closest('.question-template');
            const optionsContainer = questionTemplate.querySelector('.options-container');
            const optionCount = optionsContainer.querySelectorAll('.option').length;
            const questionIndex = Array.from(document.querySelectorAll('.question-template')).indexOf(questionTemplate)-1;
            const optionTemplate = document.createElement('div');
            optionTemplate.classList.add('form-group', 'option');
            optionTemplate.innerHTML = `
                <label for="options">Option</label>
                <input type="text" class="form-control options" name="questions[${questionIndex}][options][${optionCount}]">
                <label>
                    <input type="checkbox" class="correct-answer-chk" id="questions[${questionIndex}][correct_answers][${optionCount}]">
                    Correct Answer
                </label>
                <input type="hidden" class="correct-answer-hidden" value="0" name="questions[${questionIndex}][correct_answers][${optionCount}]">
                <button type="button" class="btn btn-danger remove-option">Remove Option</button>
            `;
            optionsContainer.appendChild(optionTemplate);
        } else if (event.target.classList.contains('remove-option')) {
            event.target.closest('.option').remove();
        }
    });
    document.addEventListener('change', function(event) {
        if (event.target.classList.contains('correct-answer-chk')) {
            //const hiddenInput = event.target.closest('.option').querySelector('.correct-answer-hidden');
            const hiddenInput=document.querySelector(`input[name="${event.target.id}"]`);
            hiddenInput.value = event.target.checked ? '1' : '0';
        }
    });
</script>
@endsection
