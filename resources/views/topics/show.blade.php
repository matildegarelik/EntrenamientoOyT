@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $topic->name }}</h1>

    <div class="content">
        {!! $topic->content !!}
    </div>

    @if ($topic->children->count() > 0)
        <h3>Children Topics:</h3>
        <ul class="list-group">
            @foreach ($topic->children as $child)
                <li class="list-group-item">
                    <a href="{{ route('topics.show', $child) }}">{{ $child->name }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No child topics available.</p>
    @endif

    @can('update', $topic)
        <a href="{{ route('topics.edit', $topic) }}" class="btn btn-primary">Edit</a>
    @endcan

    @can('delete', $topic)
        <form action="{{ route('topics.destroy', $topic) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    @endcan

    <!-- TEST -->
    @if($topic->test)
        <h1>Test</h1>
        <form method="POST" action="{{ route('tests.submit', $topic->test->id) }}">
            @csrf
            @foreach($topic->test->questions as $index => $question)
                <div class="form-group">
                    <label>{{ $question->question }}</label>
                    @if($question->type == 'multiple')
                        @foreach($question->options as $optionIndex => $option)
                            <div>
                                <input type="checkbox" name="answers[{{ $index }}][]" value="{{ $optionIndex }}">
                                {{ $option }}
                            </div>
                        @endforeach
                    @else
                        @foreach($question->options as $optionIndex => $option)
                            <div>
                                <input type="radio" name="answers[{{ $index }}][answer]" value="{{ $optionIndex }}">
                                {{ $option }}
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    @endif
</div>
@endsection

@section('scripts')
<script>
    
</script>
@endsection