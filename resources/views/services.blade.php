@extends('layouts.app')

@section('title', 'Services - PME Bénin')
@section('page-class', 'services-page')

@section('content')
    <section class="hero services-hero" style="background: linear-gradient(180deg, rgba(15, 34, 19, 0.18), rgba(15, 34, 19, 0.28)), url('/images/services-hero.svg') center/cover no-repeat;">
        <div class="page-hero-content">
            <small>Services</small>
            <h1>Des services web pensés pour une marketplace locale et durable.</h1>
            <p>Nous proposons une solution progressive : site vitrine, pages vendeur, catalogue produits et préparation à la marketplace.</p>
        </div>
    </section>

    <section class="section">
        <p class="section-title">Ce que nous proposons</p>
        <p class="section-subtitle">Une solution complète pour construire le site de ton projet et préparer l’arrivée de la marketplace.</p>
        <div class="features-grid">
            <div class="feature-card">
                <strong>Site vitrine</strong>
                <h3>Accueil, pages clés, visuels symboliques</h3>
                <p>Une page d’accueil claire, des sections À propos, Services et Contact, et un design cohérent avec l’identité béninoise.</p>
            </div>
            <div class="feature-card">
                <strong>Authentification</strong>
                <h3>Connexion et inscription</h3>
                <p>Pages dédiées pour acheteurs et vendeurs, avec gestion des comptes et interface simple.</p>
            </div>
            <div class="feature-card">
                <strong>Catalogue produits</strong>
                <h3>Valorisation des catégories locales</h3>
                <p>Textiles, artisanat, agroalimentaire : des fiches produits propres et attractives pour mettre en valeur chaque offre.</p>
            </div>
        </div>
    </section>
@endsection
