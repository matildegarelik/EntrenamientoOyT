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
                    <div class="list-group-item">
                        <a href="{{ route('topics.show', $topic) }}">{{ $topic->name }}</a>
                        @if ($topic->children->count() > 0)
                            <span class="toggle-child" data-toggle="collapse" data-target="#collapse-{{ $topic->id }}"><i class="fa fa-chevron-down"></i></span>
                            <div class="collapse" id="collapse-{{ $topic->id }}">
                                <input type="text" class="form-control mt-2 mb-2 searchTopics" data-parent="#collapse-{{ $topic->id }}" placeholder="Buscar tema">
                                @foreach ($topic->children as $child)
                                    <a href="{{ route('topics.show', $child) }}" class="list-group-item list-group-item-action pl-4">{{ $child->name }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
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
                <a class="collapse-item" href="/">Mis Tarjetas</a>
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
    var searchInputs = document.querySelectorAll('.searchTopics');
    searchInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            var filter, div, items, a, i, txtValue;
            filter = this.value.toUpperCase();
            div = document.querySelector(this.getAttribute('data-parent'));
            items = div.getElementsByClassName('list-group-item list-group-item-action');
            for (i = 0; i < items.length; i++) {
                a = items[i].textContent || items[i].innerText;
                if (a.toUpperCase().indexOf(filter) > -1) {
                    items[i].style.display = "";
                } else {
                    items[i].style.display = "none";
                }
            }
        });
    });
</script>