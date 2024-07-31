@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Configuración de Dificultad de Cartas</h1>
    <form action="{{ route('profile.update_card_settings') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="card_very_easy_days">Días para Muy Fácil</label>
            <input type="number" class="form-control" id="card_very_easy_days" name="card_very_easy_days" value="{{ old('card_very_easy_days', $user->card_very_easy_days) }}">
        </div>
        <div class="form-group">
            <label for="card_easy_days">Días para Fácil</label>
            <input type="number" class="form-control" id="card_easy_days" name="card_easy_days" value="{{ old('card_easy_days', $user->card_easy_days) }}">
        </div>
        <div class="form-group">
            <label for="card_medium_days">Días para Medio</label>
            <input type="number" class="form-control" id="card_medium_days" name="card_medium_days" value="{{ old('card_medium_days', $user->card_medium_days) }}">
        </div>
        <div class="form-group">
            <label for="card_hard_days">Días para Difícil</label>
            <input type="number" class="form-control" id="card_hard_days" name="card_hard_days" value="{{ old('card_hard_days', $user->card_hard_days) }}">
        </div>
        <div class="form-group">
            <label for="card_very_hard_days">Días para Muy Difícil</label>
            <input type="number" class="form-control" id="card_very_hard_days" name="card_very_hard_days" value="{{ old('card_very_hard_days', $user->card_very_hard_days) }}">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection
