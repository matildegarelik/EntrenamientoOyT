@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Topic</h1>
    <form method="POST" action="{{ route('topics.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="parent_id">Parent Topic <?=$parent_id?></label>
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="">None</option>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $parent_id == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="topic-content" name="content"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
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
    // Añadir el botón para seleccionar fragmento
    editor.ui.registry.addButton('selectFragmentButton', {
      text: 'Seleccionar Fragmento',
      onAction: function () {
        const selectedText = editor.selection.getContent({ format: 'html' });
        if (selectedText) {
          // Envolver el texto seleccionado en un span con la clase 'fragment'
          console.log(selectedText)
          editor.execCommand('mceInsertContent', false, '<span class="fragment">' + selectedText + '</span>');
        }
      },
      onSetup: function (buttonApi) {
        // Habilitar el botón solo cuando haya texto seleccionado
        function toggleButtonState() {
          const selectedText = editor.selection.getContent();
          buttonApi.setDisabled(selectedText.length === 0);
        }

        // Suscribirse a los eventos relevantes para verificar la selección de texto
        editor.on('NodeChange keyup', toggleButtonState);
        return function () {
          editor.off('NodeChange keyup', toggleButtonState);
        };
      }
    });

    // Añadir el botón para quitar fragmento
    editor.ui.registry.addButton('removeFragmentButton', {
      text: 'Quitar Fragmento',
      onAction: function () {
        const selectedContent = editor.selection.getContent({ format: 'html' });
        
        // Crear un contenedor temporal para manipular el contenido seleccionado
        const tempContainer = document.createElement('div');
        tempContainer.innerHTML = selectedContent;

        // Eliminar todos los spans con la clase 'fragment'
        tempContainer.querySelectorAll('span.fragment').forEach(span => {
          while (span.firstChild) {
            span.parentNode.insertBefore(span.firstChild, span);
          }
          span.parentNode.removeChild(span);
        });

        // Reemplazar el contenido seleccionado con el contenido sin spans
        editor.selection.setContent(tempContainer.innerHTML);
      },
      onSetup: function (buttonApi) {
        // Habilitar el botón solo cuando haya un fragmento seleccionado
        function toggleButtonState() {
          const selectedContent = editor.selection.getContent({ format: 'html' });
          const tempContainer = document.createElement('div');
          tempContainer.innerHTML = selectedContent;
          const hasFragment = tempContainer.querySelector('span.fragment') !== null;
          buttonApi.setDisabled(!hasFragment);
        }

        // Suscribirse a los eventos relevantes para verificar la selección de fragmento
        editor.on('NodeChange keyup', toggleButtonState);
        return function () {
          editor.off('NodeChange keyup', toggleButtonState);
        };
      }
    });
  }
});


    </script>
@endsection


