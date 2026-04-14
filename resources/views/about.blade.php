@extends('layouts.app')

@section('title', 'À propos - PME Bénin')
@section('page-class', 'about-page')

@section('content')
    <section class="hero about-hero" style="background-image: url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=80');">
        <div class="page-hero-content">
            <small>À propos</small>
            <h1>Un projet pensé pour valoriser les talents et produits du Bénin.</h1>
            <p>Nous construisons une vitrine digitale qui raconte l’histoire des artisans, du textile et de l’agroalimentaire local.</p>
        </div>
    </section>

    <section class="section">
        <p class="section-title">Pourquoi PME Bénin ?</p>
        <p class="section-subtitle">Un site qui transforme l’identité locale en une présence digitale professionnelle, prête à accueillir acheteurs béninois et internationaux.</p>
        <div class="cards-grid">
            <div class="content-card">
                <strong>Identité locale</strong>
                <h3>Mettre en valeur le savoir-faire béninois</h3>
                <p>Chaque produit et chaque vendeur bénéficie d’une présentation claire, authentique et respectueuse de son origine.</p>
            </div>
            <div class="content-card">
                <strong>Image professionnelle</strong>
                <h3>Créer la confiance avant la marketplace</h3>
                <p>Une page d’accueil soignée rassure les visiteurs, donne de la légitimité au projet et facilite l’inscription.</p>
            </div>
            <div class="content-card">
                <strong>Visibilité mondiale</strong>
                <h3>Rassembler producteurs locaux et acheteurs internationaux</h3>
                <p>La plateforme aide à ouvrir les produits béninois au monde sans perdre le lien avec les racines.</p>
            </div>
        </div>
    </section>
@endsection
