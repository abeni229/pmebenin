<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Connexion - PME Bénin</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <style>
            :root {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                background: #f7faf4;
                color: #1d2c20;
            }
            * { box-sizing: border-box; }
            html, body { min-height: 100%; margin: 0; }
            body {
                background: linear-gradient(180deg, #edf6ec 0%, #f7fbf7 100%);
                display: grid;
                place-items: center;
                padding: 2rem;
            }
            a { color: #2c4f34; text-decoration: none; }
            .page {
                width: min(980px, 100%);
                border-radius: 2rem;
                overflow: hidden;
                box-shadow: 0 35px 90px rgba(46, 98, 56, 0.12);
                background: #ffffff;
                display: grid;
                grid-template-columns: 1.1fr 0.9fr;
                min-height: 560px;
            }
            .hero {
                position: relative;
                background: url('https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=900&q=80') center/cover no-repeat;
                min-height: 100%;
            }
            .hero::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(35,73,34,0.15), rgba(21,41,23,0.45));
            }
            .hero-content {
                position: relative;
                z-index: 1;
                padding: 3rem;
                color: #f8faf8;
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
                color: rgba(248,250,248,0.9);
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
                color: #1f3326;
            }
            .form-panel p {
                margin: 0;
                color: #4f6755;
                line-height: 1.8;
            }
            .alert {
                padding: 1rem 1.2rem;
                border-radius: 1rem;
                background: #e8f6e8;
                color: #26442c;
                border: 1px solid rgba(72, 134, 63, 0.18);
            }
            .form {
                margin-top: 1rem;
                display: grid;
                gap: 1rem;
            }
            .form label {
                font-size: 0.95rem;
                color: #324a3a;
                font-weight: 600;
            }
            .form input {
                width: 100%;
                border: 1px solid #d5dfd1;
                border-radius: 1rem;
                padding: 0.95rem 1rem;
                font-size: 1rem;
                background: #f8fbf7;
                color: #1e2e24;
            }
            .form button {
                margin-top: 0.5rem;
                border: none;
                border-radius: 999px;
                background: linear-gradient(135deg, #7ccf55, #f3c863);
                color: #152713;
                padding: 1rem 1.4rem;
                font-size: 1rem;
                font-weight: 700;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                box-shadow: 0 18px 38px rgba(86, 138, 70, 0.16);
            }
            .form button:hover { transform: translateY(-2px); }
            .form-note {
                font-size: 0.95rem;
                color: #5d745f;
            }
            .form-error {
                color: #8c281a;
                font-size: 0.92rem;
            }
            .small-link {
                margin-top: 1.25rem;
                color: #2e4f34;
            }
            @media (max-width: 900px) {
                .page { grid-template-columns: 1fr; }
                .hero { min-height: 280px; }
                .hero-content { padding: 2rem; }
            }
        </style>
    </head>
    <body>
        <div class="page">
            <section class="hero">
                <div class="hero-content">
                    <h1>Connexion</h1>
                    <p>Entre dans ton espace vendeur ou acheteur et commence à gérer tes produits locaux sur PME Bénin.</p>
                </div>
            </section>
            <section class="form-panel">
                <h2>Connecte-toi</h2>
                <p>Utilise ton adresse mail pour accéder à ton tableau de bord et découvrir les fonctionnalités.</p>

                @if(session('status'))
                    <div class="alert">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert" style="background: #ffe7e4; color: #7a2c22; border-color: rgba(162, 47, 32, 0.18);">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/login" class="form">
                    @csrf
                    <label for="email">Adresse email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

                    <label for="password">Mot de passe</label>
                    <input id="password" type="password" name="password" required>

                    <button type="submit">Connexion</button>
                </form>

                <p class="form-note">Pas encore inscrit ? <a href="/register">Créer un compte</a></p>
            </section>
        </div>
    </body>
</html>
