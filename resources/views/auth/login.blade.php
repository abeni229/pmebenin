@php
$slides = [
    'https://images.unsplash.com/photo-1542435503-956c469947f6?q=80&w=1800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1556761175-5973dc0f32d7?q=80&w=1800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&w=1800&auto=format&fit=crop',
];
@endphp

@extends('layouts.auth')
@section('title', 'Connexion — PME Bénin')

@section('brand-eyebrow', 'Espace membre')
@section('brand-title', 'Bienvenue sur votre espace professionnel.')
@section('brand-desc', 'Gérez vos produits, suivez vos commandes et développez votre activité sur la marketplace béninoise.')

@section('content')

<p class="form-kicker">Espace membre</p>
<h1 class="form-heading">Connexion</h1>
<p class="form-sub">Heureux de vous revoir. Entrez vos identifiants pour continuer.</p>

@if(session('status'))
    <div class="alert alert-ok"><span>✓</span> {{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-err"><span>!</span> {{ $errors->first() }}</div>
@endif

<form method="POST" action="/login" novalidate>
    @csrf

    <div class="field">
        <label for="email">Adresse email</label>
        <div class="input-box">
            <input id="email" type="email" name="email"
                class="{{ $errors->has('email') ? 'err' : '' }}"
                value="{{ old('email') }}"
                required autofocus autocomplete="email"
                placeholder="vous@exemple.com">
        </div>
    </div>

    <div class="field">
        <label for="password">Mot de passe</label>
        <div class="input-box pw-wrap">
            <input id="password" type="password" name="password"
                class="{{ $errors->has('password') ? 'err' : '' }}"
                required autocomplete="current-password"
                placeholder="••••••••">
            <button type="button" class="pw-eye" aria-label="Afficher le mot de passe">👁</button>
        </div>
    </div>

    <div class="form-opts">
        <label class="check-label">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Se souvenir de moi
        </label>
        <a href="{{ route('password.request') }}" class="link-gold">Mot de passe oublié ?</a>
    </div>

    <button type="submit" class="btn-submit">Se connecter</button>
</form>

<p class="form-footer">
    Pas encore de compte ? <a href="{{ route('register') }}" class="link-green">Inscrivez-vous</a>
</p>

@endsection