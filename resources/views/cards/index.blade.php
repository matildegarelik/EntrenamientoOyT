@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cards</h1>
    @if( $cards->count()>0)
        <div id="card-counter" class="mb-3">
            Card <span id="current-card">1</span> of <span id="total-cards">{{ $cards->count() }}</span>
        </div>
    @endif
    <div class="mb-3">
        <a href="{{ route('cards.index', ['filter' => 'new']) }}" 
            @if($filter=='new') class="btn btn-primary" @else class="btn btn-light" style="border: 1px solid #000" @endif
        >New Cards</a>
        <a href="{{ route('cards.index', ['filter' => 'pending']) }}" 
            @if($filter=='pending') class="btn btn-primary" @else class="btn btn-light" style="border: 1px solid #000" @endif
        >Pending Cards</a>
    </div>
    <div id="no-cards" style="display: none">
        <p>No cards available.</p>
    </div>
    @if(!$cards->isEmpty())
    <div id="card-content" style="display: none;">
        <h4>Question: <span id="card-question">{{ $cards->first()->question }}</span></h4>
        <div id="card-fragment">{{ $cards->first()->fragment }}</div>
        <div class="mt-3">
            <button class="btn btn-primary" onclick="showNextCard()">Show Next Card</button>
        </div>
        <div class="mt-3">
            <button class="btn btn-secondary" onclick="updateNextReminder(5)">Easy (5 days)</button>
            <button class="btn btn-secondary" onclick="updateNextReminder(4)">Moderate (4 days)</button>
            <button class="btn btn-secondary" onclick="updateNextReminder(3)">Normal (3 days)</button>
            <button class="btn btn-secondary" onclick="updateNextReminder(2)">Hard (2 days)</button>
            <button class="btn btn-secondary" onclick="updateNextReminder(1)">Very Hard (1 day)</button>
        </div>
    </div>
    <div id="initial-content">
        <h4>Question: <span id="initial-question">{{ $cards->first()->question }}</span></h4>
        <button class="btn btn-primary" onclick="showCard()">Show Answer</button>
    </div>
    @endif
</div>

<script>
    let currentIndex = 0;
    let cards = @json($cards);

    if (cards.length==0 || cards.data.length == 0) {
        document.getElementById('no-cards').style.display = 'block';
    } else {
        document.getElementById('current-card').innerText = currentIndex + 1;
        document.getElementById('total-cards').innerText = cards.data.length;
    }

    function showCard() {
        if (cards.data.length == 0) return;

        document.getElementById('initial-content').style.display = 'none';
        document.getElementById('card-content').style.display = 'block';
        const card = cards.data[currentIndex];
        document.getElementById('card-fragment').innerHTML = card.fragment;
        document.getElementById('card-question').innerText = card.question;
        document.getElementById('initial-question').innerText = card.question;

        // Actualiza el contador
        document.getElementById('current-card').innerText = currentIndex + 1;
    }

    function showNextCard() {
        //console.log(cards)
        if (cards.data.length == 0){
            document.getElementById('no-cards').style.display = 'block';
            document.getElementById('card-content').style.display = 'none';
            document.getElementById('initial-content').style.display = 'none';
            return;

        }

        currentIndex++;
        if (currentIndex >= cards.data.length) {
            currentIndex = 0;
        }

        const card = cards.data[currentIndex];
        document.getElementById('card-fragment').innerHTML = card.fragment;
        document.getElementById('card-question').innerText = card.question;
        document.getElementById('initial-question').innerText = card.question;

        // Actualiza el contador
        document.getElementById('current-card').innerText = currentIndex + 1;

        
        document.getElementById('card-content').style.display = 'none';
        document.getElementById('initial-content').style.display = 'block';
    }

    function removeCardFromDisplay(cardId) {
        cards.data = cards.data.filter(card => card.id !== cardId);
        
        if (cards.data.length > 0) {
            if (currentIndex >= cards.data.length) {
                currentIndex = cards.data.length - 1;
            }
            const card = cards.data[currentIndex];
            document.getElementById('card-fragment').innerHTML = card.fragment;
            document.getElementById('card-question').innerText = card.question;
            document.getElementById('card-content').style.display = 'block';
            document.getElementById('initial-content').style.display = 'none';

            // Actualiza el contador
            document.getElementById('current-card').innerText = currentIndex + 1;
        } else {
            // No hay más tarjetas
            document.getElementById('card-content').style.display = 'none';
            document.getElementById('initial-content').style.display = 'block';
            document.getElementById('current-card').innerText = 0;
        }
        
        document.getElementById('total-cards').innerText = cards.data.length;
    }

    function updateNextReminder(days) {
        const card = cards.data[currentIndex];
        const cardId= card.id
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
                removeCardFromDisplay(cardId);
                showNextCard();
            }
        });
    }

</script>
@endsection
