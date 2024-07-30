<li class="nav-item child-topic">
    <hr>
    <div class="d-flex justify-content-between align-items-center w-100">
        <a href="{{ route('topics.show', $child) }}" class="nav-link" aria-controls="collapse-{{ $child->id }}">{{ $child->name }}</a>
        @if ($child->children->count() > 0)
            <span class="toggle-child px-1" data-toggle="collapse" data-target="#collapse-{{ $child->id }}">
                <i class="fa fa-chevron-right"></i>
            </span>
        @endif
    </div>
    @if ($topic->children->count() > 0)
        <div class="collapse collapse-horizontal child-topic" id="collapse-{{ $child->id }}">
            <ul class="child-topics nav flex-column">
                @foreach ($child->children as $grandChild)
                    @include('topics.child', ['child' => $grandChild])
                @endforeach
            </ul>
        </div>
    @endif
</li>
