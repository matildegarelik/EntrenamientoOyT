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
            // Obtener el contenido seleccionado como bloques de HTML
            let selectedContent = editor.selection.getContent({ format: 'html' });
            if (selectedContent) {
              selectedContent = selectedContent.replace(/<\/?p>/g, '<br>');

              // Eliminar <br> adicionales al inicio y al final si no los necesitas
              selectedContent = selectedContent.replace(/^<br>/, '').replace(/<br>$/, '');

                // Envolver el contenido seleccionado en un span con la clase 'fragment'
                const wrappedContent = '<span class="fragment">' + selectedContent + '</span>';
                editor.execCommand('mceInsertContent', false, wrappedContent);
            }
        },onSetup: function (buttonApi) {
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
        // Obtener el nodo actual en la posición del cursor
        const node = editor.selection.getNode();

        // Verificar si el nodo actual es un fragmento
        if (node && node.classList.contains('fragment')) {
          // Reemplazar el fragmento con su contenido interno, eliminando el span
          editor.dom.remove(node, true);
        }
      },
      onSetup: function (buttonApi) {
        function toggleButtonState() {
          // Obtener el nodo actual en la posición del cursor
          const node = editor.selection.getNode();

          // Habilitar el botón si el nodo es un fragmento
          buttonApi.setDisabled(!(node && node.classList.contains('fragment')));
        }

        // Suscribirse a los eventos relevantes para verificar la posición del cursor
        editor.on('NodeChange', toggleButtonState);

        // Limpiar la suscripción cuando se destruye el botón
        return function () {
          editor.off('NodeChange', toggleButtonState);
        };
      }
    });
  }
});


    </script>
@endsection


