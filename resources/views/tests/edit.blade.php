@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Test</h1>
    <form action="{{ route('tests.update', $test) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="topic_id">Topic</label>
            <select class="form-control" id="topic_id" name="topic_id" required>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $topic->id == $test->topic_id ? 'selected' : '' }}>{{ $topic->name }}</option>
                @endforeach
            </select>
        </div>
        <div id="questions">
            @foreach ($test->questions as $index => $question)
            <div class="form-group">
                <label for="question_{{ $index }}">Question</label>
                <input type="text" class="form-control" id="question_{{ $index }}" name="questions[{{ $index }}][question]" value="{{ $question->question }}" required>
                <label for="options_{{ $index }}">Options</label>
                <input type="text" class="form-control" id="options_{{ $index }}" name="questions[{{ $index }}][options]" value="{{ implode(',', $question->options) }}" required>
                <label for="correct_answers_{{ $index }}">Correct Answers</label>
                <input type="text" class="form-control" id="correct_answers_{{ $index }}" name="questions[{{ $index }}][correct_answers]" value="{{ implode(',', $question->correct_answers) }}" required>
            </div>
            @endforeach
        </div>
        <button type="button" id="add-question" class="btn btn-secondary">Add Question</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
document.getElementById('add-question').addEventListener('click', function() {
    var index = document.querySelectorAll('#questions .form-group').length;
    var questionGroup = `
        <div class="form-group">
            <label for="question_${index}">Question</label>
            <input type="text" class="form-control" id="question_${index}" name="questions[${index}][question]" required>
            <label for="options_${index}">Options</label>
            <input type="text" class="form-control" id="options_${index}" name="questions[${index}][options]" required>
            <label for="correct_answers_${index}">Correct Answers</label>
            <input type="text" class="form-control" id="correct_answers_${index}" name="questions[${index}][correct_answers]" required>
        </div>
    `;
    document.getElementById('questions').insertAdjacentHTML('beforeend', questionGroup);
});
</script>
@endsection
