@extends('layouts.app')
@section('title', 'Accueil — PME Bénin')

@section('content')
<div class="content-pane">

    {{-- Hero --}}
    <div class="home-hero-inner">
        <div class="home-hero-grid">
            <div class="hero-copy" data-reveal>
                <span class="badge">Marketplace béninoise</span>
                <h1>Vendre et acheter des produits béninois avec confiance.</h1>
                <p>Une plateforme pensée pour l'artisanat, le textile et l'agroalimentaire local avec un design premium et une navigation simple.</p>
                <div class="hero-btns">
                    <a href="{{ route('register') }}" class="btn btn-primary">Créer un compte</a>
                    <a href="/services" class="btn btn-secondary">Nos services</a>
                </div>
            </div>

            <div class="hero-visual" data-reveal
                style="background-image: url('https://images.unsplash.com/photo-1579705745811-a32bef7856a3?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
                <div class="hero-visual-label">
                    <small>Marché local</small>
                    <strong>Des produits béninois présentés avec impact.</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Catégories --}}
    <div class="inner-section">
        <p class="section-label">Catalogue</p>
        <h2 class="section-title">Catégories de produits</h2>
        <div class="grid-3">
            <div class="card" data-reveal>
                <span class="card-tag">Textile</span>
                <h3>Pagne, wax et mode béninoise</h3>
                <p>Des collections locales conçues par des artisans du Bénin, disponibles à la vente sur la plateforme.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag">Agroalimentaire</span>
                <h3>Saveurs du terroir</h3>
                <p>Épices, fruits et spécialités béninoises pour les marchés locaux et internationaux.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag gold">Artisanat</span>
                <h3>Déco et savoir-faire</h3>
                <p>Objets artisanaux authentiques, faits main et adaptés au e-commerce.</p>
            </div>
        </div>
    </div>

    {{-- Produits vedettes --}}
    <div class="inner-section">
        <p class="section-label">Aperçu</p>
        <h2 class="section-title">Découvrez notre catalogue</h2>
        <div class="prod-grid">
            <article class="prod-card" data-reveal>
                <div class="prod-img" style="background-image:url('https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=900&q=80')"></div>
                <div class="prod-body">
                    <span class="prod-cat">Textile</span>
                    <div class="prod-name">Pagne wax artisan</div>
                    <p class="prod-desc">Une étoffe béninoise vibrante, détaillée par un artisan local pour un e-shop stylé.</p>
                </div>
                <div class="prod-footer">
                    <span class="prod-price">18 000 XOF</span>
                    <span class="prod-meta">Vendeur approuvé</span>
                </div>
            </article>
            <article class="prod-card" data-reveal>
                <div class="prod-img" style="background-image:url('https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=900&q=80')"></div>
                <div class="prod-body">
                    <span class="prod-cat">Agroalimentaire</span>
                    <div class="prod-name">Huile de palme premium</div>
                    <p class="prod-desc">Produit local authentique avec une présentation élégante pour rassurer les acheteurs.</p>
                </div>
                <div class="prod-footer">
                    <span class="prod-price">12 500 XOF</span>
                    <span class="prod-meta">Livraison rapide</span>
                </div>
            </article>
            <article class="prod-card" data-reveal>
                <div class="prod-img" style="background-image:url('https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80')"></div>
                <div class="prod-body">
                    <span class="prod-cat">Artisanat</span>
                    <div class="prod-name">Objet déco en bois</div>
                    <p class="prod-desc">Une pièce unique qui combine design contemporain et savoir-faire béninois.</p>
                </div>
                <div class="prod-footer">
                    <span class="prod-price">24 000 XOF</span>
                    <span class="prod-meta">Quantité limitée</span>
                </div>
            </article>
        </div>
    </div>

    {{-- Fonctionnalités --}}
    <div class="inner-section">
        <p class="section-label">Fonctionnalités</p>
        <h2 class="section-title">Une vraie vitrine pour les talents du Bénin</h2>
        <div class="grid-3">
            <div class="card" data-reveal>
                <span class="card-tag">Accès</span>
                <h3>Connexion et inscription sécurisées</h3>
                <p>Deux parcours dédiés pour acheteurs et vendeurs, avec protection contre la force brute et vérification par email.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag">Navigation</span>
                <h3>Interface claire et accessible</h3>
                <p>Chaque page importante est accessible depuis la barre de navigation, sur desktop comme sur mobile.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag gold">Gestion</span>
                <h3>Tableaux de bord complets</h3>
                <p>Vendeurs, acheteurs et administrateurs disposent chacun d'un espace dédié à leur rôle.</p>
            </div>
        </div>
    </div>

</div>
@endsection