@php
$slides = [
    'https://images.unsplash.com/photo-1733503747506-773e56e4078f?q=80&w=1800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1521334884684-d80222895322?q=80&w=1800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1598524374912-cf4a7eed3c08?q=80&w=1800&auto=format&fit=crop',
];
@endphp

@extends('layouts.auth')
@section('title', 'Inscription — PME Bénin')

@section('brand-eyebrow', 'Rejoindre la plateforme')
@section('brand-title', 'Ouvrez votre boutique ou accédez au catalogue local.')
@section('brand-desc', 'Des milliers de produits béninois vous attendent. Créez votre compte en moins de 2 minutes.')

@section('xstyles')
.role-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 1.25rem; }
.role-card {
    position: relative; cursor: pointer;
    border: 1.5px solid #DDD8CC;
    border-radius: 0.75rem; padding: 1rem 1.1rem;
    background: var(--sand); display: flex; flex-direction: column; gap: 0.25rem;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.role-card input { position: absolute; opacity: 0; width: 0; height: 0; }
.role-card:has(input:checked) {
    border-color: var(--green-2); background: #EFF7F2;
    box-shadow: 0 0 0 3px rgba(45, 82, 66, 0.10);
}
.role-card-title { font-size: 0.9rem; font-weight: 700; color: var(--ink); }
.role-card-desc  { font-size: 0.78rem; color: var(--muted); line-height: 1.5; }
.strength-track { height: 3px; border-radius: 99px; background: #DDD8CC; margin-top: 0.45rem; overflow: hidden; }
.strength-fill  { height: 100%; border-radius: 99px; width: 0; transition: width 0.4s, background 0.4s; }
.strength-txt   { font-size: 0.75rem; color: var(--muted); margin-top: 0.3rem; }
.field-label-inline { display: flex; justify-content: space-between; align-items: baseline; }
.field-label-inline span { font-size: 0.78rem; font-weight: 400; color: var(--muted); }
@endsection

@section('content')

<p class="form-kicker">Créer un compte</p>
<h1 class="form-heading">Inscription</h1>
<p class="form-sub">Quelques informations suffisent pour commencer.</p>

@if($errors->any())
    <div class="alert alert-err">
        <span>!</span>
        <div>
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    </div>
@endif

<form method="POST" action="/register" novalidate>
    @csrf

    {{-- Rôle --}}
    <div class="role-row">
        <label class="role-card">
            <input type="radio" name="role" value="buyer" {{ old('role','buyer')==='buyer'?'checked':'' }}>
            <span class="role-card-title">Acheteur</span>
            <span class="role-card-desc">Parcourir et acheter des produits locaux</span>
        </label>
        <label class="role-card">
            <input type="radio" name="role" value="seller" {{ old('role')==='seller'?'checked':'' }}>
            <span class="role-card-title">Vendeur</span>
            <span class="role-card-desc">Publier et gérer mes produits</span>
        </label>
    </div>

    {{-- Nom + Email --}}
    <div class="field-row">
        <div class="field">
            <label for="name">Nom complet</label>
            <div class="input-box">
                <input id="name" type="text" name="name"
                    class="{{ $errors->has('name') ? 'err' : '' }}"
                    value="{{ old('name') }}" required autofocus autocomplete="name"
                    placeholder="Koffi Amavi">
            </div>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <div class="input-box">
                <input id="email" type="email" name="email"
                    class="{{ $errors->has('email') ? 'err' : '' }}"
                    value="{{ old('email') }}" required autocomplete="email"
                    placeholder="vous@exemple.com">
            </div>
        </div>
    </div>

    {{-- Téléphone + Localisation --}}
    <div class="field-row">
        <div class="field">
            <div class="field-label-inline">
                <label for="phone">Téléphone</label>
                <span>Optionnel</span>
            </div>
            <div class="input-box">
                <input id="phone" type="tel" name="phone"
                    value="{{ old('phone') }}" autocomplete="tel"
                    placeholder="+229 01 …">
            </div>
        </div>
        <div class="field">
            <div class="field-label-inline">
                <label for="location">Localisation</label>
                <span>Optionnel</span>
            </div>
            <div class="input-box">
                <input id="location" type="text" name="location"
                    value="{{ old('location') }}"
                    placeholder="Cotonou, Bénin">
            </div>
        </div>
    </div>

    {{-- Mot de passe --}}
    <div class="field">
        <label for="password">Mot de passe</label>
        <div class="input-box pw-wrap">
            <input id="password" type="password" name="password"
                class="{{ $errors->has('password') ? 'err' : '' }}"
                required autocomplete="new-password"
                placeholder="Min. 8 car., majuscule, chiffre, symbole">
            <button type="button" class="pw-eye" aria-label="Afficher">👁</button>
        </div>
        <div class="strength-track"><div class="strength-fill" id="sf"></div></div>
        <p class="strength-txt" id="st">Sécurité du mot de passe</p>
    </div>

    <div class="field">
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <div class="input-box pw-wrap">
            <input id="password_confirmation" type="password" name="password_confirmation"
                required autocomplete="new-password" placeholder="••••••••">
            <button type="button" class="pw-eye" aria-label="Afficher">👁</button>
        </div>
    </div>

    <button type="submit" class="btn-submit">Créer mon compte</button>
</form>

<p class="form-footer">
    Déjà inscrit ? <a href="{{ route('login') }}" class="link-green">Se connecter</a>
</p>

@endsection

@section('xscripts')
<script>
const sf = document.getElementById('sf');
const st = document.getElementById('st');
document.getElementById('password').addEventListener('input', function(){
    const v = this.value;
    let s = 0;
    if(v.length >= 8) s++;
    if(/[A-Z]/.test(v)) s++;
    if(/[0-9]/.test(v)) s++;
    if(/[^a-zA-Z0-9]/.test(v)) s++;
    const cfg = [
        {w:'0%',   c:'transparent', t:'Sécurité du mot de passe'},
        {w:'25%',  c:'#EF4444',     t:'Trop faible'},
        {w:'50%',  c:'#F97316',     t:'Faible — ajoutez majuscule ou symbole'},
        {w:'75%',  c:'#EAB308',     t:'Moyen — encore un effort'},
        {w:'100%', c:'#22C55E',     t:'Solide'},
    ];
    sf.style.width      = cfg[s].w;
    sf.style.background = cfg[s].c;
    st.textContent      = cfg[s].t;
    st.style.color      = s === 0 ? 'var(--muted)' : cfg[s].c;
});
</script>
@endsection