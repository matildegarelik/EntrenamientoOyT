@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Card Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $card->question }}</h5>
            <p class="card-text">{{ $card->fragment }}</p>
            <p>Next Reminder: {{ $card->next_reminder ?? 'Not Set' }}</p>
            <a href="{{ route('cards.edit', $card) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('cards.destroy', $card) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
