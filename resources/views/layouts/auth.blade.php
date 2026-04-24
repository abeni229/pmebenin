<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PME Bénin')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|dm-sans:300,400,500,600" rel="stylesheet"/>
    <style>
        
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
            --f-serif: 'Cormorant Garamond', Georgia, serif;
            --f-sans:  'DM Sans', ui-sans-serif, system-ui, sans-serif;
            --ease:    cubic-bezier(0.4, 0, 0.2, 1);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; min-height: 100dvh; }

        body {
            font-family: var(--f-sans);
            color: var(--ink);
            display: flex;
            align-items: stretch;
            min-height: 100dvh;
            overflow-x: hidden;
        }

        /* ── Slideshow fond plein écran ─────────────────── */
        .bg-stage {
            position: fixed;
            inset: 0;
            z-index: 0;
        }
        .bg-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.8s var(--ease);
            transform: scale(1);
            animation: kenburns 12s linear infinite alternate;
        }
        .bg-slide.active { opacity: 1; }
        @keyframes kenburns {
            from { transform: scale(1); }
            to   { transform: scale(1.06); }
        }
        /* Overlay permanent qui garantit la lisibilité partout */
        .bg-stage::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to right,
                    rgba(10, 24, 18, 0.72) 0%,
                    rgba(10, 24, 18, 0.30) 55%,
                    rgba(10, 24, 18, 0.55) 100%);
            z-index: 1;
        }

        /* ── Layout principal ───────────────────────────── */
        .auth-layout {
            position: relative;
            z-index: 2;
            display: flex;
            width: 100%;
            min-height: 100dvh;
        }

        /* ── Panneau gauche : branding + tagline ─────────── */
        .auth-brand {
            flex: 1.1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3.5rem;
            color: var(--white);
        }
        .logo-wrap {
            display: flex; align-items: center; gap: 0.9rem;
            text-decoration: none; cursor: pointer;
            transition: opacity 0.2s;
        }
        .logo-wrap:hover { opacity: 0.82; }
        .logo-gem {
            width: 2.8rem; height: 2.8rem;
            background: linear-gradient(135deg, var(--gold-xl), var(--gold));
            border-radius: 0.7rem;
            display: grid; place-items: center;
            font-family: var(--f-serif); font-weight: 700; font-size: 1rem;
            color: var(--green); letter-spacing: 0.04em;
            box-shadow: 0 8px 24px rgba(184, 134, 11, 0.4);
            flex-shrink: 0;
        }
        .logo-name {
            font-family: var(--f-serif);
            font-size: 1.4rem; font-weight: 600;
            letter-spacing: 0.06em;
            color: var(--white);
        }
        .logo-name em { color: var(--gold-xl); font-style: normal; }

        .brand-middle { flex: 1; display: flex; align-items: center; }
        .brand-tagline {
            max-width: 26rem;
        }
        .brand-eyebrow {
            font-size: 0.72rem; font-weight: 600;
            letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--gold-xl); margin-bottom: 1.2rem;
            display: flex; align-items: center; gap: 0.7rem;
        }
        .brand-eyebrow::before {
            content: ''; display: block;
            width: 2rem; height: 1px; background: var(--gold-xl);
        }
        .brand-tagline h1 {
            font-family: var(--f-serif);
            font-size: clamp(2.4rem, 4vw, 3.8rem);
            line-height: 1.08;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 1.4rem;
        }
        .brand-tagline p {
            font-size: 1.02rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.85;
        }

        .slide-indicators {
            display: flex; gap: 0.5rem; align-items: center;
        }
        .slide-dot {
            width: 0.4rem; height: 0.4rem;
            border-radius: 99px;
            background: rgba(255,255,255,0.35);
            transition: width 0.4s var(--ease), background 0.4s;
            cursor: pointer;
        }
        .slide-dot.active {
            width: 1.8rem;
            background: var(--gold-xl);
        }

        /* ── Panneau droit : formulaire ─────────────────── */
        .auth-form-panel {
            width: min(480px, 100%);
            flex-shrink: 0;
            background: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3.5rem 3rem;
            overflow-y: auto;
            /* Légère teinte chaude pour cohérence */
            background: linear-gradient(180deg, #FDFBF7 0%, var(--white) 100%);
        }

        /* Titre de section */
        .form-kicker {
            font-size: 0.72rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.18em;
            color: var(--gold); margin-bottom: 0.6rem;
        }
        .form-heading {
            font-family: var(--f-serif);
            font-size: clamp(2rem, 3vw, 2.6rem);
            font-weight: 600; line-height: 1.1;
            color: var(--ink); margin-bottom: 0.5rem;
        }
        .form-sub {
            font-size: 0.93rem; color: var(--muted);
            line-height: 1.75; margin-bottom: 2rem;
        }

        /* Champs */
        .field { margin-bottom: 1.25rem; }
        .field label {
            display: block; font-size: 0.85rem; font-weight: 600;
            color: var(--ink); margin-bottom: 0.45rem;
        }
        .field-opt { font-weight: 400; color: var(--muted); }
        .input-box { position: relative; }
        .input-box input, .input-box select {
            width: 100%;
            background: var(--sand);
            border: 1.5px solid transparent;
            border-radius: 0.75rem;
            padding: 0.9rem 1.1rem;
            font-size: 0.95rem;
            font-family: var(--f-sans);
            color: var(--ink);
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
            appearance: none;
        }
        .input-box input::placeholder { color: #AAB5A8; }
        .input-box input:focus, .input-box select:focus {
            outline: none;
            background: var(--white);
            border-color: var(--green-2);
            box-shadow: 0 0 0 3px rgba(45, 82, 66, 0.10);
        }
        .input-box input.err { border-color: #C0392B; }
        .pw-wrap input { padding-right: 3rem; }
        .pw-eye {
            position: absolute; right: 0.95rem; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 0;
            color: var(--muted); line-height: 1; font-size: 1rem;
            transition: color 0.2s;
        }
        .pw-eye:hover { color: var(--green); }

        /* Grille 2 cols */
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* Options */
        .form-opts {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.6rem; font-size: 0.87rem;
        }
        .check-label {
            display: flex; align-items: center; gap: 0.5rem;
            color: var(--muted); cursor: pointer;
        }
        .check-label input { accent-color: var(--green); }
        .link-gold { color: var(--gold); font-weight: 600; text-decoration: none; }
        .link-gold:hover { color: var(--gold-l); }
        .link-green { color: var(--green-2); font-weight: 700; text-decoration: none; }
        .link-green:hover { color: var(--green); }

        /* Bouton principal */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--green-2) 0%, var(--green) 100%);
            color: var(--white);
            border: none; border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            font-size: 0.97rem; font-weight: 600;
            font-family: var(--f-sans);
            cursor: pointer; letter-spacing: 0.02em;
            box-shadow: 0 10px 30px rgba(30, 58, 47, 0.25);
            transition: transform 0.2s var(--ease), box-shadow 0.2s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 16px 40px rgba(30,58,47,0.32); }
        .btn-submit:active { transform: none; }

        /* Alertes */
        .alert {
            border-radius: 0.75rem; padding: 0.85rem 1.1rem;
            margin-bottom: 1.4rem; font-size: 0.88rem; font-weight: 500;
            display: flex; gap: 0.7rem; align-items: flex-start;
        }
        .alert-err { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }
        .alert-ok  { background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; }
        .alert-info{ background: #FEFCE8; border: 1px solid #FDE68A; color: #854D0E; }

        /* Pied de page formulaire */
        .form-footer {
            margin-top: 1.6rem; text-align: center;
            font-size: 0.88rem; color: var(--muted);
        }

        /* ── Responsive ─────────────────────────────────── */
        @media (max-width: 840px) {
            .auth-brand { display: none; }
            .auth-form-panel { width: 100%; padding: 2.5rem 1.6rem; }
            .bg-stage::after {
                background: rgba(10, 24, 18, 0.55);
            }
        }
        @media (max-width: 400px) {
            .field-row { grid-template-columns: 1fr; }
        }

        @yield('xstyles')
    </style>
</head>
<body>

    {{-- Slideshow plein écran --}}
    <div class="bg-stage" id="bgStage">
        @foreach(array_merge([$slides[0] ?? ''], array_slice($slides ?? [], 1)) as $i => $slide)
            <div class="bg-slide{{ $i === 0 ? ' active' : '' }}" style="background-image:url('{{ $slide }}')"></div>
        @endforeach
    </div>

    <div class="auth-layout">

        {{-- Panneau gauche --}}
        <aside class="auth-brand">
            <a href="/" class="logo-wrap" aria-label="Retour à l'accueil PME Bénin">
                <div class="logo-gem">PM</div>
                <span class="logo-name">PME&nbsp;<em>Bénin</em></span>
            </a>

            <div class="brand-middle">
                <div class="brand-tagline">
                    <p class="brand-eyebrow">@yield('brand-eyebrow', 'Marketplace béninoise')</p>
                    <h1>@yield('brand-title', 'Vendez et achetez en confiance.')</h1>
                    <p>@yield('brand-desc', 'Artisanat, textile, agroalimentaire — la vitrine digitale du Bénin.')</p>
                </div>
            </div>

            <div class="slide-indicators" id="slideIndc"></div>
        </aside>

        {{-- Panneau droit --}}
        <main class="auth-form-panel">
            @yield('content')
        </main>

    </div>

    <script>
    (function(){
        const slides = document.querySelectorAll('.bg-slide');
        const indc   = document.getElementById('slideIndc');
        if (!slides.length) return;

        // Créer les indicateurs
        slides.forEach((_, i) => {
            const d = document.createElement('button');
            d.className = 'slide-dot' + (i === 0 ? ' active' : '');
            d.setAttribute('aria-label', 'Slide ' + (i+1));
            d.addEventListener('click', () => goTo(i));
            indc.appendChild(d);
        });

        let cur = 0;
        function goTo(n) {
            slides[cur].classList.remove('active');
            indc.children[cur].classList.remove('active');
            cur = n;
            slides[cur].classList.add('active');
            indc.children[cur].classList.add('active');
        }
        setInterval(() => goTo((cur + 1) % slides.length), 6000);

        // Toggle mot de passe
        document.querySelectorAll('.pw-eye').forEach(btn => {
            btn.addEventListener('click', () => {
                const inp = btn.previousElementSibling;
                const show = inp.type === 'password';
                inp.type = show ? 'text' : 'password';
                btn.textContent = show ? '🙈' : '👁';
            });
        });
    })();
    </script>
    @yield('xscripts')
</body>
</html>