@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($card) ? 'Edit Card' : 'Create Card' }}</h1>

    <form action="{{ isset($card) ? route('cards.update', $card) : route('cards.store') }}" method="POST">
        @csrf
        @if (isset($card))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="topic_id">Topic</label>
            <select class="form-control" id="topic_id" name="topic_id" required>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" {{ isset($card) && $card->topic_id == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="fragment">Fragment</label>
            <textarea class="form-control" id="fragment" name="fragment" rows="3" required>{{ isset($card) ? $card->fragment : '' }}</textarea>
        </div>
        <div class="form-group">
            <label for="question">Question</label>
            <input type="text" class="form-control" id="question" name="question" value="{{ isset($card) ? $card->question : '' }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($card) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection
