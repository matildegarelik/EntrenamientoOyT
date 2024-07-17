<div class="list-group-item child-topic" data-name="{{ $child->name }}">
    <a href="{{ route('topics.show', $child) }}" class="list-group-item-action pl-4">{{ $child->name }}</a>
    @if ($child->children->count() > 0)
        <span class="toggle-child" data-toggle="collapse" data-target="#collapse-{{ $child->id }}">
            <i class="fa fa-chevron-down"></i>
        </span>
        <div class="collapse" id="collapse-{{ $child->id }}">
            <input type="text" class="form-control mt-2 mb-2 searchTopics" data-parent="#collapse-{{ $child->id }}" placeholder="Buscar tema">
            <div class="child-topics">
                @foreach ($child->children as $grandChild)
                    @include('topics.child', ['child' => $grandChild])
                @endforeach
            </div>
        </div>
    @endif
</div>
