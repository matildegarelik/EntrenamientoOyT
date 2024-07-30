<nav class="sidebar bg-primary sidebar-dark accordion" id="accordionSidebar">
        <ul class="nav flex-column">
                
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="sidebar-brand-text mx-2">Entrenamiento <sup>OyT</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard 
            <li class="nav-item active">
                <a class="nav-link" href="/">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Página Principal</span></a>
            </li>-->

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Temas -->
            <li class="nav-item primer_nivel">
                <a class="nav-link" data-toggle="collapse" href="#topicsToggle" data-target="#topicsToggle" role="button" aria-expanded="false" aria-controls="topicsToggle">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Temas</span>
                </a>
                <div id="topicsToggle" class="collapse collapse-horizontal normal">
                    <ul class="nav flex-column bg-white">
                            <input type="text" class="form-control mt-2 mb-2 searchTopics p-1 ml-2" placeholder="Buscar tema principal" style="width: 90% !important">
                            @foreach ($topics as $topic)
                                @if (!$topic->parent_id)

                                <li class="nav-item parent-topic">
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                      <a href="{{ route('topics.show', $topic) }}" class="parent-topic-link nav-link">{{ $topic->name }}</a>
                                      @if ($topic->children->count() > 0)
                                        <span class="toggle-child  px-1" data-toggle="collapse" data-target="#collapse-{{ $topic->id }}">
                                          <i class="fa fa-chevron-right"></i>
                                        </span>
                                      @endif
                                    </div>
                                    @if ($topic->children->count() > 0)
                                      <div class="collapse collapse-horizontal child-topic" id="collapse-{{ $topic->id }}">
                                        <ul class="child-topics nav flex-column" style='margin-left:20px'>
                                          @foreach ($topic->children as $child)
                                            @include('topics.child', ['child' => $child])
                                          @endforeach
                                        </ul>
                                      </div>
                                    @endif
                                  </li>
                                  
                                @endif
                            @endforeach
                        </ul>
                    
                </div>
            </li>

            <!-- Divider -->
            <!--<hr class="sidebar-divider">-->

            <li class="nav-item primer_nivel">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Tarjetas</span>
                </a>
                <div id="collapseTwo" class="collapse normal" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{route('cards.index')}}">Mis Tarjetas</a>
                    </div>
                </div>
            </li>


            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item primer_nivel">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Preguntas</span>
                </a>
                <div id="collapsePages" class="collapse normal" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{route('tests.results')}}">Mis cuestionarios</a>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
    </nav>
<!-- End of Sidebar -->

<script>
    // Filtrar temas según la búsqueda en cada sección
    document.querySelector('.searchTopics').addEventListener('input', function() {
        const filter = this.value.toUpperCase();
        const items = document.querySelectorAll('.parent-topic');
        
        items.forEach(item => {
            const text = item.textContent || item.innerText;
            item.style.display = text.toUpperCase().includes(filter) ? '' : 'none';
        });
    });

    
    $(document).ready(function() {
        $('.nav-item .nav-link').on('click', function(e) {
            const $this = $(this);
            const $target = $($this.data('target'));

            if (!$target.hasClass('show')) {
                $('.collapse').collapse('hide');
            }

            $target.collapse('toggle');
        });

        $('.nav-item i').on('click', function(e) {
            e.preventDefault();
            $('.nav-item').removeClass('active');
            $(this).closest('li.nav-item').addClass('active').parents('li.nav-item').addClass('active');
        });
    });


</script>