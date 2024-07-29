@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $topic->name }}</h1>

    <div class="content">
        @php
            $fragments = explode('<span class="fragment">', $topic->content);
        @endphp
        @foreach ($fragments as $index => $fragment)
            @if ($index > 0)
                @php
                    list($text, $rest) = explode('</span>', $fragment, 2);
                    $text = trim($text);
                    $isTitle = preg_match('/^<h[1-6]/i', $text);
                @endphp
                @if ($isTitle)
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input me-2" type="checkbox" name="fragments[]" value="{{ $text }}" id="fragment-{{ $index }}" onclick="openModal(this, '{{ $index }}')">
                        <label class="form-check-label" for="fragment-{{ $index }}">
                            {!! $text !!}
                        </label>
                    </div>
                    {!! $rest !!}
                @else
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="fragments[]" value="{{ $text }}" id="fragment-{{ $index }}" onclick="openModal(this, '{{ $index }}')">
                        <label class="form-check-label" for="fragment-{{ $index }}">
                            {!! $text !!}
                        </label>
                    </div>
                    {!! $rest !!}
                @endif
            @else
                {!! $fragment !!}
            @endif
        @endforeach
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
</div>

<!-- Modal -->
<div class="modal fade" id="fragmentModal" tabindex="-1" role="dialog" aria-labelledby="fragmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="modalForm" method="POST" action="{{ route('cards.store') }}">
                @csrf
                <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                <input type="hidden" name="fragment" id="modalFragment">
                <div class="modal-header">
                    <h5 class="modal-title" id="fragmentModalLabel">Edit Fragment and Add Question</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modalFragmentContent">Fragment Content</label>
                        <textarea class="form-control" id="modalFragmentContent" name="fragment_content" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="modalQuestion">Question</label>
                        <input type="text" class="form-control" id="modalQuestion" name="question">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save to Card</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(checkbox, index) {
        const fragment = checkbox.value;
        document.getElementById('modalFragment').value = fragment;
        document.getElementById('modalFragmentContent').value = fragment;
        new bootstrap.Modal(document.getElementById('fragmentModal')).show();
    }

    document.getElementById('modalForm').addEventListener('submit', function(e) {
        const fragment = document.getElementById('modalFragmentContent').value;
        document.getElementById('modalFragment').value = fragment;
    });
</script>
@endsection
