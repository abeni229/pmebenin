<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inscription - PME Bénin</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <style>
            :root {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                background: #eef7ee;
                color: #1f3328;
            }
            * { box-sizing: border-box; }
            html, body { min-height: 100%; margin: 0; }
            body {
                background: linear-gradient(180deg, #f2faf2 0%, #eef7ee 100%);
                display: grid;
                place-items: center;
                padding: 2rem;
            }
            a { color: #2e4f34; text-decoration: none; }
            .page {
                width: min(980px, 100%);
                border-radius: 2rem;
                overflow: hidden;
                box-shadow: 0 35px 95px rgba(59, 110, 60, 0.12);
                background: #ffffff;
                display: grid;
                grid-template-columns: 1.1fr 0.9fr;
                min-height: 620px;
            }
            .hero {
                position: relative;
                background-image: linear-gradient(180deg, rgba(15, 34, 19, 0.15), rgba(15, 34, 19, 0.25)), url('https://images.pexels.com/photos/1666079/pexels-photo-1666079.jpeg?auto=compress&cs=tinysrgb&h=900&w=1200');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 100%;
            }
            .hero::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(58,100,58,0.15), rgba(23,41,22,0.45));
            }
            .hero-content {
                position: relative;
                z-index: 1;
                padding: 3rem;
                color: #eef7ee;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                height: 100%;
            }
            .hero-content h1 {
                margin: 0;
                font-size: clamp(2rem, 3vw, 2.7rem);
                line-height: 1.05;
            }
            .hero-content p {
                margin-top: 1.2rem;
                max-width: 18rem;
                color: rgba(238,247,238,0.9);
                line-height: 1.8;
            }
            .form-panel {
                padding: 3rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 1rem;
            }
            .form-panel h2 {
                margin: 0;
                font-size: 2rem;
                color: #1c3125;
            }
            .form-panel p {
                margin: 0;
                color: #4e6d57;
                line-height: 1.8;
            }
            .alert {
                padding: 1rem 1.2rem;
                border-radius: 1rem;
                background: #eef7e7;
                color: #2f5630;
                border: 1px solid rgba(84, 147, 85, 0.18);
            }
            .form {
                margin-top: 1rem;
                display: grid;
                gap: 1rem;
            }
            .form label {
                font-size: 0.95rem;
                color: #324b39;
                font-weight: 600;
            }
            .form input,
            .form select {
                width: 100%;
                border: 1px solid #d6e2d5;
                border-radius: 1rem;
                padding: 0.95rem 1rem;
                font-size: 1rem;
                background: #f8faf6;
                color: #1f3328;
            }
            .form button {
                margin-top: 0.5rem;
                border: none;
                border-radius: 999px;
                background: linear-gradient(135deg, #7bcf5a, #f0c869);
                color: #162916;
                padding: 1rem 1.4rem;
                font-size: 1rem;
                font-weight: 700;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                box-shadow: 0 18px 38px rgba(86, 140, 70, 0.18);
            }
            .form button:hover { transform: translateY(-2px); }
            .form-note {
                font-size: 0.95rem;
                color: #5e775d;
            }
            .form-error {
                color: #843126;
                font-size: 0.92rem;
            }
            @media (max-width: 900px) {
                .page { grid-template-columns: 1fr; }
                .hero { min-height: 300px; }
                .hero-content { padding: 2rem; }
            }
        </style>
    </head>
    <body>
        <div class="page">
            <section class="hero">
                <div class="hero-content">
                    <h1>Inscription</h1>
                    <p>Rejoins PME Bénin comme acheteur ou vendeur et commence à présenter vos produits locaux au monde entier.</p>
                </div>
            </section>
            <section class="form-panel">
                <h2>Créer un compte</h2>
                <p>Inscris-toi en quelques minutes et accède à un espace sécurisé pour gérer ton activité.</p>

                @if(session('status'))
                    <div class="alert">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert" style="background: #ffe7e4; color: #7a2c22; border-color: rgba(162, 47, 32, 0.18);">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/register" class="form">
                    @csrf
                    <label for="name">Nom complet</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

                    <label for="email">Adresse email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>

                    <label for="role">Je suis</label>
                    <select id="role" name="role" required>
                        <option value="buyer" {{ old('role') === 'buyer' ? 'selected' : '' }}>Acheteur</option>
                        <option value="seller" {{ old('role') === 'seller' ? 'selected' : '' }}>Vendeur / Artisan</option>
                    </select>

                    <label for="phone">Téléphone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}">

                    <label for="location">Localisation</label>
                    <input id="location" type="text" name="location" value="{{ old('location') }}">

                    <label for="password">Mot de passe</label>
                    <input id="password" type="password" name="password" required>

                    <button type="submit">Créer mon compte</button>
                </form>

                <p class="form-note">Déjà inscrit ? <a href="/login">Se connecter</a></p>
            </section>
        </div>
    </body>
</html>
