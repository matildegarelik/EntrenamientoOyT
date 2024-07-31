@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $topic->name }}</h1>

    <div class="content" id="topic-content">
        @php
            $content = $topic->content;
            $pattern = '/(<h1[^>]*>.*?<\/h1>)/i';
            $parts = preg_split($pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            $cardIndex = 0;
        @endphp
    
        @foreach ($parts as $part)
            @if (preg_match($pattern, $part))
                @if ($cardIndex > 0)
                    </div>
                    </div>
                    </div>
                @endif
                @php $cardIndex++; @endphp
                <div class="card shadow mb-4">
                    <a href="#collapseCard{{ $cardIndex }}" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCard{{ $cardIndex }}">
                        <h6 class="m-0 font-weight-bold text-primary">{!! $part !!}</h6>
                    </a>
                    <div class="collapse" id="collapseCard{{ $cardIndex }}">
                        <div class="card-body">
            @else
                @php
                    $fragments = explode('<span class="fragment">', $part);
                @endphp
                @foreach ($fragments as $index => $fragment)
                    @if ($index > 0)
                        @php
                            list($text, $rest) = explode('</span>', $fragment, 2);
                            $text = trim($text);
                        @endphp
                        <span class="fragment">
                            {!! $text !!}
                            <input class="form-check-input me-2 ml-auto" type="checkbox" name="fragments[]" value="{{ $text }}" id="fragment-{{ $index }}" onclick="openModal(this, '{{ $index }}')">
                        </span>
                        {!! $rest !!}
                    @else
                        {!! $fragment !!}
                    @endif
                @endforeach
            @endif
        @endforeach
    
        @if ($cardIndex > 0)
            </div>
            </div>
            </div>
        @endif
    </div>
    
    
    <!-- TEST -->
    @if($topic->test)
    <hr>
    <div class="container_fluid">
        <h2>Test: {{ $topic->test->name }}</h2>
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
    </div>
    @endif

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
                    <h5 class="modal-title" id="fragmentModalLabel">Create Card</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

    $(document).ready(function() {
        $('#fragmentModal').on('hidden.bs.modal', function () {
            // Deseleccionar todos los checkboxes
            $('input[name="fragments[]"]').prop('checked', false);
        });
    });

</script>
@endsection
