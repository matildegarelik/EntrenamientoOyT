@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cards</h1>
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
    <hr>
    @if( $cards->count()>0)
        <div class="card" id="card-card">
            <div class="card-header">
           
            
                Topic: <div id="card-topic" href="{{ route('topics.show', $cards->first()->topic) }}">{{$cards->first()->topic->name}}</div>
                <div id="card-counter" class="mb-3" style="text-align:right">
                    Card <span id="current-card">1</span> of <span id="total-cards">{{ $cards->count() }}</span>
                </div>
            
            </div>

            <div class="card-body">
        

            
                <div id="card-content" style="display: none;">
                    <h4>Question: <span id="card-question">{{ $cards->first()->question }}</span></h4>
                    <div id="card-fragment">{{ $cards->first()->fragment }}</div>
                    <div class="mt-3">
                        <button class="btn btn-primary" onclick="showNextCard()">Show Next Card</button>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-secondary" onclick="updateNextReminder(<?=$user->card_very_easy_days?>)">Very Easy (<?=$user->card_very_easy_days?> days)</button>
                        <button class="btn btn-secondary" onclick="updateNextReminder(<?=$user->card_easy_days?>)">Easy (<?=$user->card_easy_days?> days)</button>
                        <button class="btn btn-secondary" onclick="updateNextReminder(<?=$user->card_medium_days?>)">Medium (<?=$user->card_medium_days?> days)</button>
                        <button class="btn btn-secondary" onclick="updateNextReminder(<?=$user->card_hard_days?>)">Hard (<?=$user->card_hard_days?> days)</button>
                        <button class="btn btn-secondary" onclick="updateNextReminder(<?=$user->card_very_hard_days?>)">Very Hard (<?=$user->card_very_hard_days?> day)</button>
                    </div>
                </div>
                <div id="initial-content">
                    <h4>Question: <span id="initial-question">{{ $cards->first()->question }}</span></h4>
                    <button class="btn btn-info" onclick="showCard()">Show Answer</button>
                    <button class="btn btn-warning" onclick="editCard()">Edit</button>
                    <button class="btn btn-danger" onclick="deleteCard()">Delete</button>
                </div>
            
            </div>
        </div>
    @endif
</div>

<script>
    let currentIndex = 0;
    let cards = @json($cards);

    if (cards.length==0 || cards.data.length == 0) {
        document.getElementById('no-cards').style.display = 'block';
        document.getElementById('card-card').style.display = 'none';
    } else {
        document.getElementById('current-card').innerText = currentIndex + 1;
        document.getElementById('total-cards').innerText = cards.data.length;
        updateCardTopic(cards.data[currentIndex]);
    }

    function updateCardTopic(card) {
        if (!card.topic) {
            document.getElementById('card-topic').innerHTML = 'No topic assigned';
            return;
        }

        let topicHierarchy = [];
        if (card.topic.parents) {
            card.topic.parents.forEach(function(parent) {
                topicHierarchy.push(`<a href="/topics/${parent.id}">${parent.name}</a>`);
            });
        }
        topicHierarchy.push(`<a href="/topics/${card.topic.id}">${card.topic.name}</a>`);

        document.getElementById('card-topic').innerHTML = topicHierarchy.join(' > ');
    }


    function showCard() {
        if (cards.data.length == 0) return;

        document.getElementById('initial-content').style.display = 'none';
        document.getElementById('card-content').style.display = 'block';
        const card = cards.data[currentIndex];
        document.getElementById('card-fragment').innerHTML = card.fragment;
        document.getElementById('card-question').innerText = card.question;
        document.getElementById('initial-question').innerText = card.question;
        updateCardTopic(card)

        // Actualiza el contador
        document.getElementById('current-card').innerText = currentIndex + 1;
    }

    function showNextCard() {
        //console.log(cards)
        if (cards.data.length == 0){
            document.getElementById('no-cards').style.display = 'block';
            document.getElementById('card-content').style.display = 'none';
            document.getElementById('initial-content').style.display = 'none';
            document.getElementById('card-card').style.display = 'none';
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
        updateCardTopic(card); 

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
            updateCardTopic(card); 

            // Actualiza el contador
            document.getElementById('current-card').innerText = currentIndex + 1;
        } else {
            // No hay más tarjetas
            document.getElementById('card-content').style.display = 'none';
            document.getElementById('initial-content').style.display = 'block';
            document.getElementById('current-card').innerText = 0;
            document.getElementById('card-card').style.display = 'none';
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

    function editCard() {
        if (cards.data.length == 0) return;

        const card = cards.data[currentIndex];
        const modalContent = `
            <div class="modal" tabindex="-1" role="dialog" id="editCardModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Card</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editCardForm">
                                <div class="form-group">
                                    <label for="editFragment">Fragment</label>
                                    <input type="text" class="form-control" id="editFragment" name="fragment" value="${card.fragment}">
                                </div>
                                <div class="form-group">
                                    <label for="editQuestion">Question</label>
                                    <input type="text" class="form-control" id="editQuestion" name="question" value="${card.question}">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="updateCard(${card.id})">Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.insertAdjacentHTML('beforeend', modalContent);
        $('#editCardModal').modal('show');

        $('#editCardModal').on('hidden.bs.modal', function () {
            $(this).remove();
        });
    }

    function updateCard(cardId) {
    const form = document.getElementById('editCardForm');
    const formData = new FormData(form);
    formData.append('_method', 'PUT'); // Agrega esto para "simular" el método PUT en Laravel
    const data = Object.fromEntries(formData);

    fetch(`/cards/${cardId}`, {
        method: 'POST', // El método HTTP real debe ser POST
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify(data),
    }).then(response => {
        if (response.ok) {
            $('#editCardModal').modal('hide');
            alert('Card updated successfully.');
            const index = cards.data.findIndex(card => card.id === cardId);
            if (index !== -1) {
                cards.data[index].fragment = data.fragment;
                cards.data[index].question = data.question;
            }
            showCard();
        } else {
            alert('Failed to update card.');
        }
    });
}

    function deleteCard() {
        if (cards.data.length == 0) return;

        const card = cards.data[currentIndex];
        if (confirm('Are you sure you want to delete this card?')) {
            fetch(`/cards/${card.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            }).then(response => {
                if (response.ok) {
                    alert('Card deleted successfully.');
                    removeCardFromDisplay(card.id);
                    showNextCard();
                } else {
                    alert('Failed to delete card.');
                }
            });
        }
    }


</script>
@endsection
