@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Topic</h1>
    <form method="POST" action="{{ route('topics.update', $topic) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $topic->name }}" required>
        </div>
        <div class="form-group">
            <label for="parent_id">Parent Topic</label>
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="">None</option>
                @foreach ($topics as $top)
                    <option value="{{ $top->id }}" {{ $topic->parent_id == $top->id ? 'selected' : '' }}>{{ $top->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="topic-content" name="content">{{ $topic->content }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    tinymce.init({
        selector: '#topic-content',
        plugins: 'advlist autolink lists link image charmap print preview anchor code',
        toolbar: 'selectFragmentButton removeFragmentButton | undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | code',
        toolbar_mode: 'floating',
        height: 400,
        content_style: '.fragment { background-color: yellow; border: 1px solid orange; }',
        setup: function (editor) {
            editor.ui.registry.addButton('selectFragmentButton', {
                text: 'Seleccionar Fragmento',
                onAction: function () {
                    let selectedContent = editor.selection.getContent({ format: 'html' });
                    if (selectedContent) {
                        selectedContent = selectedContent.replace(/<\/?p>/g, '<br>');
                        selectedContent = selectedContent.replace(/^<br>/, '').replace(/<br>$/, '');
                        const wrappedContent = '<span class="fragment">' + selectedContent + '</span>';
                        editor.execCommand('mceInsertContent', false, wrappedContent);
                    }
                },
                onSetup: function (buttonApi) {
                    function toggleButtonState() {
                        const selectedText = editor.selection.getContent();
                        buttonApi.setDisabled(selectedText.length === 0);
                    }
                    editor.on('NodeChange keyup', toggleButtonState);
                    return function () {
                        editor.off('NodeChange keyup', toggleButtonState);
                    };
                }
            });

            editor.ui.registry.addButton('removeFragmentButton', {
                text: 'Quitar Fragmento',
                onAction: function () {
                    const node = editor.selection.getNode();
                    if (node && node.classList.contains('fragment')) {
                        editor.dom.remove(node, true);
                    }
                },
                onSetup: function (buttonApi) {
                    function toggleButtonState() {
                        const node = editor.selection.getNode();
                        buttonApi.setDisabled(!(node && node.classList.contains('fragment')));
                    }
                    editor.on('NodeChange', toggleButtonState);
                    return function () {
                        editor.off('NodeChange', toggleButtonState);
                    };
                }
            });
        }
    });
</script>
@endsection
