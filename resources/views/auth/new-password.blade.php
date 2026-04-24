@extends('layouts.auth')
@section('title', 'Nouveau mot de passe')
@section('content')
<div class="glass-card single-col">
    <div class="form-side">
        <div class="brand" style="justify-content: center; margin-bottom: 2rem;">PME<span>Bénin</span></div>
        <h2 style="text-align: center; font-size: 1.7rem;">Nouveau mot de passe</h2>
        <p style="text-align: center;">Veuillez saisir votre nouveau mot de passe.</p>

        @if($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/reset-password">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <div class="form-group">
                <label for="email">Adresse email</label>
                <div class="input-wrapper">
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $request->email ?? '') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <div class="input-wrapper password-field">
                    <input id="password" type="password" name="password" class="form-control" required>
                    <button type="button" class="toggle-password" aria-label="Afficher/masquer">👁️</button>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <div class="input-wrapper password-field">
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                    <button type="button" class="toggle-password" aria-label="Afficher/masquer">👁️</button>
                </div>
            </div>

            <button type="submit" class="btn-submit" style="margin-top: 1rem;">Réinitialiser le mot de passe</button>
        </form>
    </div>
</div>
@endsection
