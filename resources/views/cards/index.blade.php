@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cards</h1>
    <div class="mb-3">
        <a href="{{ route('cards.index', ['filter' => 'new']) }}" class="btn btn-primary">New Cards</a>
        <a href="{{ route('cards.index', ['filter' => 'pending']) }}" class="btn btn-primary">Pending Cards</a>
    </div>
    @if ($cards->isEmpty())
        <p>No cards available.</p>
    @else
        <div id="card-content" style="display: none;">
            <h4>Question: {{ $cards->first()->question }}</h4>
            <div id="card-fragment">{{ $cards->first()->fragment }}</div>
            <div class="mt-3">
                <button class="btn btn-primary" onclick="showNextCard()">Show Next Card</button>
            </div>
            <div class="mt-3">
                <button class="btn btn-secondary" onclick="updateNextReminder({{ $cards->first()->id }}, 5)">Easy (5 days)</button>
                <button class="btn btn-secondary" onclick="updateNextReminder({{ $cards->first()->id }}, 4)">Moderate (4 days)</button>
                <button class="btn btn-secondary" onclick="updateNextReminder({{ $cards->first()->id }}, 3)">Normal (3 days)</button>
                <button class="btn btn-secondary" onclick="updateNextReminder({{ $cards->first()->id }}, 2)">Hard (2 days)</button>
                <button class="btn btn-secondary" onclick="updateNextReminder({{ $cards->first()->id }}, 1)">Very Hard (1 day)</button>
            </div>
        </div>
        <div id="initial-content">
            <h4>Question: {{ $cards->first()->question }}</h4>
            <button class="btn btn-primary" onclick="showCard()">Show Answer</button>
        </div>
    @endif
</div>

<script>
    let currentIndex = 0;
    const cards = @json($cards);

    function showCard() {
        document.getElementById('initial-content').style.display = 'none';
        document.getElementById('card-content').style.display = 'block';
    }

    function showNextCard() {
        currentIndex++;
        if (currentIndex < cards.data.length) {
            document.getElementById('card-fragment').innerHTML = cards.data[currentIndex].fragment;
        } else {
            alert('No more cards.');
        }
    }

    function updateNextReminder(cardId, days) {
        fetch(`/cards/${cardId}/update-next-reminder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ days: days }),
        }).then(response => {
            if (response.ok) {
                alert('Next reminder updated.');
                showNextCard();
            }
        });
    }
</script>
@endsection
