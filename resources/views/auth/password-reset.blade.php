@extends('layouts.auth')
@section('title', 'Mot de passe oublié')
@section('content')
<div class="glass-card single-col">
    <div class="form-side" style="text-align: center;">
        <div class="brand" style="justify-content: center; margin-bottom: 2rem;">PME<span>Bénin</span></div>
        <h2 style="font-size: 1.7rem;">Mot de passe oublié ?</h2>
        <p style="text-align: center;">Indiquez-nous simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation.</p>

        @if(session('status'))
            <div class="alert success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/forgot-password" style="text-align: left;">
            @csrf
            <div class="form-group">
                <label for="email">Adresse email</label>
                <div class="input-wrapper">
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="vous@exemple.com">
                </div>
            </div>

            <button type="submit" class="btn-submit">Envoyer le lien</button>
        </form>
        
        <div class="form-footer">
            <a href="/login">← Retour à la connexion</a>
        </div>
    </div>
</div>
@endsection
