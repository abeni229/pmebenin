<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'PME Bénin')</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                color: #1f3326;
                background: #f7faf4;
            }
            * { box-sizing: border-box; }
            html, body {
                margin: 0;
                min-height: 100vh;
            }
            body {
                color: #1f3326;
                position: relative;
                background: #f7faf4;
            }
            .page-background {
                position: fixed;
                inset: 0;
                z-index: -1;
                background: url('https://images.unsplash.com/photo-1542435503-956c469947f6?q=80&w=1074&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover fixed #f7faf4;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                pointer-events: none;
            }
            a { color: inherit; text-decoration: none; }
            .page { width: min(1280px, 100%); margin: 0 auto; padding: 0 1.5rem 2.5rem; position: relative; z-index: 1; }
            .header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.5rem 0; }
            .logo { display: inline-flex; align-items: center; gap: 0.8rem; font-weight: 700; font-size: 1.1rem; color: #1c3428; }
            .logo-mark { width: 2.3rem; height: 2.3rem; border-radius: 1rem; display: grid; place-items: center; color: #fff; font-size: 1rem; background: linear-gradient(135deg, #7dcf61, #f2b954); box-shadow: 0 18px 40px rgba(80, 133, 58, 0.18); }
            .nav-links { display: flex; flex-wrap: wrap; gap: 1.3rem; font-weight: 500; color: #4a6b53; }
            .nav-links a:hover { color: #2f522e; }
            .top-actions { display: flex; flex-wrap: wrap; gap: 0.9rem; }
            .button { border-radius: 999px; padding: 0.95rem 1.7rem; font-weight: 700; border: 1px solid transparent; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
            .button-primary { background: linear-gradient(135deg, #7dcf61, #f2b954); color: #172817; box-shadow: 0 18px 40px rgba(77, 138, 62, 0.22); }
            .button-secondary { background: rgba(40, 66, 36, 0.08); color: #283f2d; border-color: rgba(40, 66, 36, 0.15); }
            .button:hover { transform: translateY(-2px); }
            .hero { position: relative; overflow: hidden; border-radius: 2rem; background: #fff; box-shadow: 0 30px 80px rgba(79, 125, 72, 0.1); }
            .hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at top left, rgba(124, 207, 79, 0.18), transparent 28%), radial-gradient(circle at bottom right, rgba(243, 189, 86, 0.14), transparent 24%); pointer-events: none; }
            .hero-grid { display: grid; gap: 2rem; padding: 3.5rem 2rem; }
            .hero-copy, .hero-visual, .feature-card, .category-card { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.9s ease forwards; }
            .hero-copy { animation-delay: 0.15s; }
            .hero-visual { animation-delay: 0.25s; }
            .feature-card { animation-duration: 1s; }
            .category-card { animation-duration: 1s; }
            .feature-card:nth-child(2), .category-card:nth-child(2) { animation-delay: 0.25s; }
            .feature-card:nth-child(3), .category-card:nth-child(3) { animation-delay: 0.4s; }
            .hero-copy h1, .hero-copy p, .section-title, .feature-card h3, .category-card h3 { transition: transform 0.3s ease, opacity 0.3s ease; }
            .hero-visual:hover { transform: scale(1.01); transition: transform 0.5s ease; }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
            @media (min-width: 900px) { .hero-grid { grid-template-columns: 1fr 1fr; } }
            .hero-copy { position: relative; z-index: 1; }
            .hero-copy h1 { font-size: clamp(3rem, 5vw, 4rem); line-height: 1.02; color: #173020; margin: 0; }
            .hero-copy p { margin-top: 1.6rem; color: #4f6b58; font-size: 1.05rem; max-width: 42rem; }
            .hero-buttons { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; }
            .hero-image { position: relative; min-height: 420px; border-radius: 1.8rem; overflow: hidden; background-size: cover; background-position: center; }
            .hero-image::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(21, 35, 21, 0.28)); }
            .hero-image-text { position: absolute; left: 1.8rem; bottom: 1.8rem; color: #fff; z-index: 1; max-width: 18rem; }
            .hero-image-text small { display: inline-block; margin-bottom: 0.75rem; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(255,255,255,0.85); font-size: 0.8rem; }
            .hero-image-text h2 { margin: 0; font-size: 1.55rem; line-height: 1.2; }
            .section { margin-top: 4.5rem; }
            .section-title { font-size: 2rem; font-weight: 700; color: #1d3026; margin-bottom: 0.9rem; }
            .section-subtitle { max-width: 690px; color: #52705e; font-size: 1rem; line-height: 1.8; }
            .features-grid { display: grid; gap: 1.5rem; margin-top: 2rem; }
            .cards-grid { display: grid; gap: 1.5rem; margin-top: 2rem; }
            @media (min-width: 820px) { .features-grid, .cards-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
            .feature-card, .category-card, .content-card { background: #fff; border-radius: 1.7rem; padding: 2rem; border: 1px solid rgba(132, 165, 108, 0.16); box-shadow: 0 22px 40px rgba(75, 130, 67, 0.08); }
            .feature-card strong, .category-card strong { display: inline-block; padding: 0.55rem 0.95rem; border-radius: 999px; background: rgba(124, 207, 79, 0.12); color: #385f39; font-size: 0.85rem; }
            .feature-card h3, .category-card h3, .content-card h3 { margin: 1.25rem 0 0.75rem; font-size: 1.25rem; color: #1f3328; }
            .feature-card p, .category-card p, .content-card p { color: #516957; line-height: 1.8; }
            .about-hero, .services-hero, .contact-hero { position: relative; min-height: 420px; border-radius: 2rem; overflow: hidden; display: grid; align-items: flex-end; background-size: cover; background-position: center; background-repeat: no-repeat; }
            .about-hero::after, .services-hero::after, .contact-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15, 34, 19, 0.10), rgba(15, 34, 19, 0.22)); }
            .page-hero-content { position: relative; z-index: 1; padding: 3rem; color: #fff; max-width: 520px; }
            .page-hero-content h1 { margin: 0; font-size: clamp(2.5rem, 4vw, 3.4rem); line-height: 1.05; }
            .page-hero-content p { margin-top: 1.2rem; color: rgba(255,255,255,0.9); max-width: 36rem; line-height: 1.8; }
            .contact-grid { display: grid; gap: 1.3rem; margin-top: 2rem; }
            @media (min-width: 820px) { .contact-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
            .contact-card { padding: 2rem; border-radius: 1.7rem; background: #ffffff; border: 1px solid rgba(121, 165, 106, 0.16); box-shadow: 0 20px 45px rgba(82, 128, 66, 0.08); }
            .contact-card h3 { margin-top: 0; color: #1f3328; }
            .contact-card p { margin-top: 0.8rem; color: #4f6f58; line-height: 1.8; }
            .footer { margin-top: 4.5rem; padding-top: 2.5rem; border-top: 1px solid rgba(79, 123, 68, 0.15); display: flex; flex-wrap: wrap; justify-content: space-between; gap: 1.5rem; color: #4a624f; }
            .footer-block { min-width: 220px; }
            .footer-block h4 { margin-bottom: 0.9rem; font-size: 1rem; color: #203323; }
            .footer-block p, .footer-block a { font-size: 0.95rem; color: #4a624f; line-height: 1.8; }
            .footer-block a:hover { color: #2c442d; }
            .alert { margin-top: 1rem; padding: 1rem 1.3rem; border-radius: 1.2rem; background: #e7f7e5; border: 1px solid rgba(68, 141, 74, 0.2); color: #2f582f; font-weight: 600; }
            @media (max-width: 900px) { .hero-grid, .cards-grid, .features-grid, .contact-grid { grid-template-columns: 1fr; } .page { padding: 0 1rem 2rem; } }
        </style>
        @stack('styles')
    </head>
    <body class="@yield('page-class') ds-root">
        <div class="page-background"></div>
        <div class="page ds-container">
            <header class="header ds-header">
                <a href="/" class="logo ds-logo">
                    <span class="logo-mark ds-logo-mark">PM</span>
                    <span>PME Bénin</span>
                </a>
                <nav class="nav-links ds-nav">
                    <a href="/">Accueil</a>
                    <a href="{{ route('shop') }}">Boutique</a>
                    <a href="{{ route('cart') }}">Panier @if(count(session('cart', [])) > 0) ({{ count(session('cart', [])) }}) @endif</a>
                    <a href="/about">À propos</a>
                    <a href="/services">Services</a>
                    <a href="/contact">Contact</a>
                </nav>
                <div class="top-actions ds-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="button ds-button ds-button-secondary">Tableau de bord</a>
                        <form method="POST" action="/logout" style="display:inline;">
                            @csrf
                            <button type="submit" class="button ds-button ds-button-primary">Déconnexion</button>
                        </form>
                    @else
                        <a href="/login" class="button ds-button ds-button-secondary">Connexion</a>
                        <a href="/register" class="button ds-button ds-button-primary">Inscription</a>
                    @endauth
                </div>
            </header>

            @yield('content')

            <footer class="footer ds-footer">
                <div class="footer-block ds-footer-block">
                    <h4>PME Bénin</h4>
                    <p>Une présence digitale construite pour valoriser l’artisanat, le textile et l’agroalimentaire béninois.</p>
                </div>
                <div class="footer-block ds-footer-block">
                    <h4>Contact</h4>
                    <p>Email : contact@pmebenin.bj</p>
                    <p>Téléphone : +229 90 00 00 00</p>
                </div>
                <div class="footer-block ds-footer-block">
                    <h4>Liens rapides</h4>
                    <p><a href="/about">À propos</a></p>
                    <p><a href="/services">Services</a></p>
                    <p><a href="/contact">Contact</a></p>
                </div>
            </footer>
        </div>
        @stack('scripts')
    </body>
</html>
