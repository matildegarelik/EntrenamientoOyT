<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-2">Entrenamiento <sup>OyT</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Página Principal</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <!--<div class="sidebar-heading">
        Interface
    </div>-->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Temas</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <!--<div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Buscar temas:</h6>
                <a class="collapse-item" href="/">Trauma</a>
                <a class="collapse-item" href="/">Spine</a>
                <a class="collapse-item" href="/">Shoulder & ELbow</a>
                <a class="collapse-item" href="/">Other</a>
            </div>-->
            <div class="list-group">
                @foreach ($topics as $topic)
                    @if (!$topic->parent_id)
                        <div class="list-group-item parent-topic" data-name="{{ $topic->name }}">
                            <a href="{{ route('topics.show', $topic) }}">{{ $topic->name }}</a>
                            @if ($topic->children->count() > 0)
                                <span class="toggle-child" data-toggle="collapse" data-target="#collapse-{{ $topic->id }}">
                                    <i class="fa fa-chevron-down"></i>
                                </span>
                                <div class="collapse" id="collapse-{{ $topic->id }}">
                                    <input type="text" class="form-control mt-2 mb-2 searchTopics" data-parent="#collapse-{{ $topic->id }}" placeholder="Buscar tema">
                                    <div class="child-topics">
                                        @foreach ($topic->children as $child)
                                            @include('topics.child', ['child' => $child])
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            
            
        </div>
    </li>

    <!-- Divider -->
    <!--<hr class="sidebar-divider">-->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Tarjetas</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{route('cards.index')}}">Mis Tarjetas</a>
            </div>
        </div>
    </li>


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Preguntas</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/">Mis cuestionarios</a>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->

<script>
    // Filtrar temas según la búsqueda en cada sección
    document.querySelectorAll('.searchTopics').forEach(input => {
        input.addEventListener('input', function() {
            const filter = this.value.toUpperCase();
            const parent = document.querySelector(this.dataset.parent);
            const items = parent.querySelectorAll('.list-group-item-action');

            items.forEach(item => {
                const text = item.textContent || item.innerText;
                item.style.display = text.toUpperCase().includes(filter) ? '' : 'none';
            });

            // Ocultar el grupo de hijos si no hay resultados
            const hasVisibleChildren = Array.from(items).some(item => item.style.display !== 'none');
            parent.closest('.parent-topic').style.display = hasVisibleChildren || text.toUpperCase().includes(filter) ? '' : 'none';
        });
    });
</script>