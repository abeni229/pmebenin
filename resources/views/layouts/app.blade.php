<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PME Bénin')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|dm-sans:300,400,500,600" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ═══════════════════════════════════════════════════════
           PME BÉNIN — APP LAYOUT
           Slideshow fond plein écran sur TOUTES les pages
           Contenu sur panneau blanc semi-opaque → lisibilité max
        ═══════════════════════════════════════════════════════ */
        :root {
            --gold:    #B8860B;
            --gold-l:  #D4A017;
            --gold-xl: #F0C040;
            --green:   #1E3A2F;
            --green-2: #2D5242;
            --green-3: #3D6B57;
            --sand:    #F5EDD8;
            --sand-2:  #EDE0C4;
            --white:   #FFFFFF;
            --ink:     #0F1F18;
            --muted:   #5A7268;
            --border:  rgba(30,58,47,0.10);
            --shadow:  0 20px 50px rgba(15,31,24,0.12);
            --shadow-l:0 32px 80px rgba(15,31,24,0.18);
            --r-xl:    1.6rem;
            --r-lg:    1.1rem;
            --r-md:    0.75rem;
            --f-serif: 'Cormorant Garamond', Georgia, serif;
            --f-sans:  'DM Sans', ui-sans-serif, system-ui, sans-serif;
            --ease:    cubic-bezier(0.4, 0, 0.2, 1);
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        html, body { margin: 0; min-height: 100vh; }
        body { font-family: var(--f-sans); color: var(--ink); }
        a { color: inherit; text-decoration: none; }

        /* ── Slideshow fond plein écran ─────────────────────────── */
        .app-bg {
            position: fixed; inset: 0; z-index: 0;
        }
        .app-slide {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
            opacity: 0;
            transition: opacity 2s var(--ease);
            animation: kb 14s ease-in-out infinite alternate;
        }
        .app-slide.active { opacity: 1; }
        @keyframes kb {
            from { transform: scale(1); }
            to   { transform: scale(1.05); }
        }
        /* Overlay chaleureux : assez dense pour que le contenu blanc soit net */
        .app-bg::after {
            content: ''; position: absolute; inset: 0;
            background:
                linear-gradient(180deg,
                    rgba(8, 20, 14, 0.68) 0%,
                    rgba(8, 20, 14, 0.50) 40%,
                    rgba(8, 20, 14, 0.65) 100%);
            z-index: 1;
        }

        /* ── Wrapper de page ────────────────────────────────────── */
        .app-root {
            position: relative; z-index: 2;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── HEADER ─────────────────────────────────────────────── */
        .site-header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(14, 26, 19, 0.78);
            backdrop-filter: blur(18px) saturate(150%);
            -webkit-backdrop-filter: blur(18px) saturate(150%);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .header-inner {
            width: min(1340px, 100%); margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: center; gap: 2rem;
            height: 4rem;
        }
        .logo {
            display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0;
        }
        .logo-gem {
            width: 2.2rem; height: 2.2rem;
            background: linear-gradient(135deg, var(--gold-xl), var(--gold));
            border-radius: 0.55rem; display: grid; place-items: center;
            font-family: var(--f-serif); font-size: 0.85rem; font-weight: 700;
            color: var(--green); letter-spacing: 0.04em;
            box-shadow: 0 4px 14px rgba(184,134,11,0.4);
        }
        .logo-name {
            font-family: var(--f-serif); font-size: 1.2rem; font-weight: 600;
            letter-spacing: 0.05em; color: var(--white);
        }
        .logo-name em { color: var(--gold-xl); font-style: normal; }

        nav.main-nav { flex: 1; display: flex; align-items: center; gap: 0.15rem; }
        nav.main-nav a {
            padding: 0.4rem 0.85rem; border-radius: 99px;
            font-size: 0.9rem; font-weight: 500;
            color: rgba(255,255,255,0.72);
            transition: color 0.2s, background 0.2s;
        }
        nav.main-nav a:hover, nav.main-nav a.is-active {
            color: var(--white);
            background: rgba(255,255,255,0.09);
        }

        .header-actions { display: flex; align-items: center; gap: 0.6rem; flex-shrink: 0; }
        .hbtn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1.2rem; border-radius: 99px;
            font-size: 0.88rem; font-weight: 600;
            font-family: var(--f-sans);
            border: 1.5px solid transparent; cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .hbtn:hover { transform: translateY(-1px); }
        .hbtn-ghost {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.80);
            border-color: rgba(255,255,255,0.14);
        }
        .hbtn-ghost:hover { background: rgba(255,255,255,0.14); }
        .hbtn-primary {
            background: linear-gradient(135deg, var(--gold-l), var(--gold));
            color: var(--green);
            box-shadow: 0 4px 16px rgba(184,134,11,0.30);
        }
        .hbtn-primary:hover { box-shadow: 0 8px 24px rgba(184,134,11,0.40); }
        .cart-pill {
            background: var(--gold-xl); color: var(--green);
            border-radius: 99px; font-size: 0.72rem; font-weight: 800;
            padding: 0 0.4rem; min-width: 1.2rem; height: 1.2rem;
            display: inline-grid; place-items: center;
        }

        /* ── PAGE BODY ──────────────────────────────────────────── */
        .app-body {
            flex: 1; padding: 2rem 0 4rem;
        }
        .container {
            width: min(1340px, 100%); margin: 0 auto; padding: 0 2rem;
        }

        /* ── PANNEAU DE CONTENU (fond blanc chaud) ─────────────── */
        /* Les sections de contenu sont sur fond blanc solide = lisibilité garantie */
        .content-pane {
            background: rgba(253, 251, 247, 0.97);
            border-radius: var(--r-xl);
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: var(--shadow-l);
            overflow: hidden;
        }

        /* ── FLASH HERO (bande image en tête de page) ──────────── */
        /* Utilisé sur About, Services, Contact — image spécifique à la page */
        .page-flash {
            position: relative;
            min-height: 340px;
            background-size: cover; background-position: center;
            display: flex; align-items: flex-end;
            border-radius: var(--r-xl) var(--r-xl) 0 0;
            overflow: hidden;
        }
        .page-flash::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg,
                rgba(8,20,14,0.08) 0%,
                rgba(8,20,14,0.65) 100%);
        }
        .page-flash-body {
            position: relative; z-index: 1;
            padding: 2.5rem 3rem; color: var(--white); max-width: 56rem;
        }
        .page-flash-kicker {
            font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.2em; color: var(--gold-xl);
            margin-bottom: 0.7rem;
            display: flex; align-items: center; gap: 0.6rem;
        }
        .page-flash-kicker::before { content:''; width:1.5rem; height:1px; background:var(--gold-xl); display:block; }
        .page-flash-body h1 {
            font-family: var(--f-serif);
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: 1.1; font-weight: 600; margin: 0 0 0.9rem;
        }
        .page-flash-body p { color: rgba(255,255,255,0.84); line-height: 1.85; font-size: 1rem; }

        /* ── SECTION INTERNE ────────────────────────────────────── */
        .inner-section { padding: 3rem; }
        .inner-section + .inner-section { border-top: 1px solid rgba(30,58,47,0.07); }

        .section-label {
            font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.18em; color: var(--gold);
            margin-bottom: 0.6rem;
        }
        .section-title {
            font-family: var(--f-serif);
            font-size: clamp(1.7rem, 2.8vw, 2.3rem);
            font-weight: 600; line-height: 1.12;
            color: var(--ink); margin: 0 0 0.7rem;
        }
        .section-sub {
            color: var(--muted); font-size: 0.97rem; line-height: 1.85;
            max-width: 62ch; margin: 0 0 2.2rem;
        }

        /* ── GRILLES ────────────────────────────────────────────── */
        .grid-3 { display: grid; gap: 1.3rem; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); }
        .grid-2 { display: grid; gap: 1.3rem; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }

        /* ── CARDS ──────────────────────────────────────────────── */
        .card {
            background: var(--white);
            border-radius: var(--r-lg);
            border: 1px solid rgba(30,58,47,0.08);
            box-shadow: 0 8px 28px rgba(15,31,24,0.07);
            padding: 1.8rem;
            transition: transform 0.25s var(--ease), box-shadow 0.25s;
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 16px 45px rgba(15,31,24,0.13); }
        .card-tag {
            display: inline-flex; padding: 0.35rem 0.85rem; border-radius: 99px;
            font-size: 0.75rem; font-weight: 700; letter-spacing: 0.06em;
            background: rgba(30,58,47,0.08); color: var(--green-2);
            margin-bottom: 1rem;
        }
        .card-tag.gold { background: rgba(184,134,11,0.10); color: var(--gold); }
        .card h3 {
            font-family: var(--f-serif); font-size: 1.3rem; font-weight: 600;
            color: var(--ink); margin: 0 0 0.6rem;
        }
        .card p { color: var(--muted); line-height: 1.85; font-size: 0.93rem; margin: 0; }

        /* ── PRODUCT CARDS ──────────────────────────────────────── */
        .prod-grid { display: grid; gap: 1.3rem; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
        .prod-card {
            background: var(--white);
            border-radius: var(--r-lg); overflow: hidden;
            border: 1px solid rgba(30,58,47,0.08);
            box-shadow: 0 8px 28px rgba(15,31,24,0.07);
            display: flex; flex-direction: column;
            transition: transform 0.25s var(--ease), box-shadow 0.25s;
        }
        .prod-card:hover { transform: translateY(-4px); box-shadow: 0 18px 48px rgba(15,31,24,0.14); }
        .prod-img { height: 200px; background-size: cover; background-position: center; flex-shrink: 0; }
        .prod-body { padding: 1.2rem; flex: 1; display: flex; flex-direction: column; gap: 0.5rem; }
        .prod-cat {
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--gold);
        }
        .prod-name { font-family: var(--f-serif); font-size: 1.1rem; color: var(--ink); font-weight: 600; }
        .prod-desc { font-size: 0.87rem; color: var(--muted); line-height: 1.7; flex: 1; }
        .prod-footer {
            padding: 0.9rem 1.2rem; border-top: 1px solid rgba(30,58,47,0.06);
            display: flex; align-items: center; justify-content: space-between;
        }
        .prod-price { font-weight: 700; font-size: 1rem; color: var(--green); font-family: var(--f-sans); }
        .prod-meta  { font-size: 0.78rem; color: var(--muted); }

        /* ── HERO PAGE D'ACCUEIL ────────────────────────────────── */
        .home-hero-inner { padding: 3.5rem 3rem; }
        .home-hero-grid { display: grid; gap: 2.5rem; align-items: center; }
        @media (min-width: 860px) { .home-hero-grid { grid-template-columns: 1fr 1fr; } }
        .hero-copy { position: relative; z-index: 1; }
        .badge {
            display: inline-flex; padding: 0.4rem 1rem; border-radius: 99px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
            background: rgba(184,134,11,0.12); color: var(--gold);
            margin-bottom: 1rem;
        }
        .hero-copy h1 {
            font-family: var(--f-serif);
            font-size: clamp(2.8rem, 5vw, 4rem);
            line-height: 1.06; font-weight: 700; color: var(--ink); margin: 0 0 1.2rem;
        }
        .hero-copy p { color: var(--muted); font-size: 1.05rem; line-height: 1.85; max-width: 44ch; }
        .hero-btns { display: flex; flex-wrap: wrap; gap: 0.9rem; margin-top: 1.8rem; }
        .btn { /* réutilisé en page */
            display: inline-flex; align-items: center; border-radius: 99px;
            padding: 0.8rem 1.6rem; font-size: 0.93rem; font-weight: 600;
            font-family: var(--f-sans); border: 1.5px solid transparent;
            cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary {
            background: linear-gradient(135deg, var(--green-2), var(--green));
            color: var(--white); box-shadow: 0 8px 24px rgba(30,58,47,0.25);
        }
        .btn-secondary {
            background: var(--sand); color: var(--green);
            border-color: var(--sand-2);
        }

        .hero-visual {
            position: relative; border-radius: var(--r-lg); overflow: hidden;
            min-height: 360px; background-size: cover; background-position: center;
        }
        .hero-visual::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, transparent 50%, rgba(8,20,14,0.6) 100%);
        }
        .hero-visual-label {
            position: absolute; bottom: 0; left: 0; right: 0;
            z-index: 1; padding: 1.6rem 1.8rem; color: var(--white);
        }
        .hero-visual-label small {
            display: block; font-size: 0.7rem; font-weight: 600;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--gold-xl); margin-bottom: 0.4rem;
        }
        .hero-visual-label strong { font-family: var(--f-serif); font-size: 1.4rem; font-weight: 600; line-height: 1.2; }

        /* ── ALERTS ─────────────────────────────────────────────── */
        .flash {
            margin-bottom: 1.5rem; padding: 0.9rem 1.2rem;
            border-radius: var(--r-md);
            background: #F0FDF4; border: 1px solid #BBF7D0;
            color: #166534; font-size: 0.9rem; font-weight: 500;
        }

        /* ── FOOTER ─────────────────────────────────────────────── */
        .site-footer {
            background: rgba(8, 20, 14, 0.82);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .footer-inner {
            width: min(1340px, 100%); margin: 0 auto; padding: 3rem 2rem 1.5rem;
            display: flex; flex-wrap: wrap; gap: 2.5rem; justify-content: space-between;
        }
        .footer-col h4 {
            font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.14em; color: var(--gold-xl); margin-bottom: 1rem;
        }
        .footer-col p, .footer-col a {
            font-size: 0.9rem; color: rgba(255,255,255,0.6); line-height: 2.1;
        }
        .footer-col a:hover { color: rgba(255,255,255,0.9); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 1.2rem 2rem; text-align: center;
            font-size: 0.8rem; color: rgba(255,255,255,0.35);
        }

        /* ── ANIMATIONS ─────────────────────────────────────────── */
        [data-reveal] {
            opacity: 0; transform: translateY(18px);
            transition: opacity 0.7s var(--ease), transform 0.7s var(--ease);
        }
        [data-reveal].visible { opacity: 1; transform: none; }

        /* ── RESPONSIVE ─────────────────────────────────────────── */
        @media (max-width: 900px) {
            .header-inner { padding: 0 1.2rem; }
            nav.main-nav { display: none; }
            .container { padding: 0 1.2rem; }
            .app-body { padding: 1.2rem 0 3rem; }
            .inner-section { padding: 2rem 1.5rem; }
            .home-hero-inner { padding: 2.5rem 1.5rem; }
            .page-flash-body { padding: 2rem 1.5rem; }
        }

        @stack('styles')
    </style>
</head>
<body>

    {{-- Slideshow fond plein écran sur TOUTES les pages --}}
    <div class="app-bg" id="appBg">
        @php
            $bgSlides = $pageSlides ?? [
                'https://images.unsplash.com/photo-1542435503-956c469947f6?q=80&w=1800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1556761175-5973dc0f32d7?q=80&w=1800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&w=1800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1521334884684-d80222895322?q=80&w=1800&auto=format&fit=crop',
            ];
        @endphp
        @foreach($bgSlides as $i => $sl)
            <div class="app-slide{{ $i === 0 ? ' active' : '' }}" style="background-image:url('{{ $sl }}')"></div>
        @endforeach
    </div>

    <div class="app-root">

        <header class="site-header">
            <div class="header-inner">
                <a href="/" class="logo">
                    <div class="logo-gem">PM</div>
                    <span class="logo-name">PME&nbsp;<em>Bénin</em></span>
                </a>

                <nav class="main-nav" aria-label="Navigation principale">
                    <a href="/"                    class="{{ request()->is('/')         ? 'is-active':'' }}">Accueil</a>
                    <a href="{{ route('shop') }}"  class="{{ request()->is('shop*')     ? 'is-active':'' }}">Boutique</a>
                    <a href="/about"               class="{{ request()->is('about')     ? 'is-active':'' }}">À propos</a>
                    <a href="/services"            class="{{ request()->is('services')  ? 'is-active':'' }}">Services</a>
                    <a href="/contact"             class="{{ request()->is('contact')   ? 'is-active':'' }}">Contact</a>
                </nav>

                <div class="header-actions">
                    @auth
                        <a href="{{ route('cart') }}" class="hbtn hbtn-ghost">
                            Panier
                            @if(count(session('cart',[])) > 0)
                                <span class="cart-pill">{{ count(session('cart',[])) }}</span>
                            @endif
                        </a>
                        <a href="{{ route('dashboard') }}" class="hbtn hbtn-ghost">Tableau de bord</a>
                        <form method="POST" action="/logout" style="display:inline">
                            @csrf
                            <button type="submit" class="hbtn hbtn-primary">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"    class="hbtn hbtn-ghost">Connexion</a>
                        <a href="{{ route('register') }}" class="hbtn hbtn-primary">Inscription</a>
                    @endauth
                </div>
            </div>
        </header>

        <div class="app-body">
            <div class="container">

                @if(session('status'))
                    <div class="flash">{{ session('status') }}</div>
                @endif

                @yield('content')

            </div>
        </div>

        <footer class="site-footer">
            <div class="footer-inner">
                <div class="footer-col">
                    <h4>PME Bénin</h4>
                    <p>La marketplace de l'artisanat,<br>du textile et de l'agroalimentaire béninois.</p>
                </div>
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <p><a href="/about">À propos</a></p>
                    <p><a href="/services">Services</a></p>
                    <p><a href="{{ route('shop') }}">Boutique</a></p>
                    <p><a href="/contact">Contact</a></p>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <p>contact@pmebenin.bj</p>
                    <p>+229 01 50 43 47 10</p>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} PME Bénin &mdash; Fait avec soin au Bénin
            </div>
        </footer>

    </div>

    <script>
    (function(){
        // ── Slideshow
        const slides = document.querySelectorAll('.app-slide');
        let cur = 0;
        if(slides.length > 1) {
            setInterval(() => {
                slides[cur].classList.remove('active');
                cur = (cur + 1) % slides.length;
                slides[cur].classList.add('active');
            }, 7000);
        }

        // ── Reveal au scroll
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
            });
        }, { threshold: 0.10 });
        document.querySelectorAll('[data-reveal]').forEach(el => obs.observe(el));
    })();
    </script>
    @stack('scripts')
</body>
</html>