@extends('layouts.auth')
@section('title', 'Vérification de l\'email')
@section('content')
<div class="glass-card single-col">
    <div class="form-side" style="text-align: center;">
        <div class="brand" style="justify-content: center; margin-bottom: 2rem;">PME<span>Bénin</span></div>
        <h2 style="font-size: 1.7rem;">Vérifiez votre adresse email</h2>
        <p style="text-align: center; margin-bottom: 1.5rem; line-height: 1.6;">
            Merci pour votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez pas reçu l'e-mail, nous nous ferons un plaisir de vous en envoyer un autre.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert success" style="margin-bottom: 1.5rem;">
                Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie lors de votre inscription.
            </div>
        @endif

        <form method="POST" action="/email/verification-notification" style="margin-bottom: 1rem;">
            @csrf
            <button type="submit" class="btn-submit">Renvoyer l'e-mail de vérification</button>
        </form>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit" style="background: none; border: none; color: rgba(255, 255, 255, 0.6); text-decoration: underline; cursor: pointer; padding: 0.5rem; font-size: 0.9rem; transition: color 0.3s;">
                Se déconnecter
            </button>
        </form>
    </div>
</div>
@endsection
