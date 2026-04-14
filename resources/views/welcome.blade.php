@extends('layouts.app')

@section('title', 'Accueil - PME Bénin')
@section('page-class', 'home-page')

@section('content')
    @if(session('status'))
        <div class="ds-alert">{{ session('status') }}</div>
    @endif

    <section class="ds-hero" aria-label="Présentation de la marketplace">
        <div class="ds-hero-grid">
            <div class="ds-hero-copy" data-reveal>
                <span class="ds-badge">Marketplace béninoise</span>
                <h1>Vendre et acheter des produits béninois avec élégance.</h1>
                <p>Une plateforme pensée pour l’artisanat, le textile et l’agroalimentaire local, avec un design premium, une navigation simple et une identité visuelle forte.</p>
                <div class="ds-hero-actions">
                    <a href="/register" class="ds-button ds-button-primary">Créer un compte</a>
                    <a href="/services" class="ds-button ds-button-secondary">Nos services</a>
                </div>
            </div>

            <div class="ds-hero-visual ds-card" data-reveal style="background: linear-gradient(180deg, rgba(15, 31, 17, 0.12), rgba(15, 31, 17, 0.45)), url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1200&q=80');">
                <div class="ds-hero-visual-text">
                    <small>Marché local</small>
                    <h2>Des produits béninois présentés avec impact.</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="ds-section" aria-label="Valeurs de la marketplace" data-reveal>
        <p class="ds-section-title">Une vraie vitrine pour les talents du Bénin</p>
        <p class="ds-section-subtitle">Le site met en avant les vendeurs locaux, les catégories de produits et les services essentiels pour une marketplace crédible.</p>
    </section>

    <section class="ds-section" aria-label="Catégories de produits">
        <p class="ds-section-title">Catégories de produits</p>
        <div class="ds-card-group three-cols">
            <article class="ds-category-card" data-reveal>
                <strong>Textile</strong>
                <h3>Pagne, wax et mode béninoise</h3>
                <p>Met en valeur les collections locales conçues par des artisans du Bénin.</p>
            </article>
            <article class="ds-category-card" data-reveal>
                <strong>Agroalimentaire</strong>
                <h3>Saveurs du terroir</h3>
                <p>Propose des épices, fruits et spécialités béninoises aux marchés locaux et internationaux.</p>
            </article>
            <article class="ds-category-card" data-reveal>
                <strong>Artisanat</strong>
                <h3>Déco et savoir-faire</h3>
                <p>Présente des objets artisanaux authentiques, faits main et adaptés au e-commerce.</p>
            </article>
        </div>
    </section>

    <section class="ds-section" aria-label="Fonctionnalités essentielles">
        <p class="ds-section-title">Fonctionnalités essentielles</p>
        <div class="ds-card-group three-cols">
            <article class="ds-feature-card" data-reveal>
                <strong>Connexion / Inscription</strong>
                <h3>Accès rapide pour clients et vendeurs</h3>
                <p>Deux pages dédiées pour se connecter ou créer un compte avec un parcours simple.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Navigation simple</strong>
                <h3>Menus clairs</h3>
                <p>Chaque page importante est accessible depuis la barre de navigation.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Visuels symboliques</strong>
                <h3>Pages distinctes</h3>
                <p>Chaque section et page peut afficher un fond illustratif adapté au thème.</p>
            </article>
        </div>
    </section>
@endsection
