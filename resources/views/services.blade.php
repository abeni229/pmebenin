@php
$pageSlides = [
    'https://images.pexels.com/photos/1181650/pexels-photo-1181650.jpeg?auto=compress&cs=tinysrgb&w=1800',
    'https://images.unsplash.com/photo-1542435503-956c469947f6?q=80&w=1800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1556761175-5973dc0f32d7?q=80&w=1800&auto=format&fit=crop',
];
@endphp

@extends('layouts.app')
@section('title', 'Services — PME Bénin')

@section('content')
<div class="content-pane" data-reveal>

    {{-- Bande image en tête --}}
    <div class="page-flash" style="background-image: url('https://images.pexels.com/photos/1181650/pexels-photo-1181650.jpeg?auto=compress&cs=tinysrgb&w=1800');">
        <div class="page-flash-body">
            <p class="page-flash-kicker">Services</p>
            <h1>Des services web pensés pour une marketplace locale et durable.</h1>
            <p>Une solution progressive : vitrine, pages vendeur, catalogue produits et préparation à la marketplace.</p>
        </div>
    </div>

    {{-- Ce que nous proposons --}}
    <div class="inner-section">
        <p class="section-label">Offre complète</p>
        <h2 class="section-title">Ce que nous proposons</h2>
        <p class="section-sub">Une solution complète pour construire votre présence digitale et préparer l'arrivée de la marketplace.</p>

        <div class="grid-3">
            <div class="card" data-reveal>
                <span class="card-tag">Vitrine</span>
                <h3>Site vitrine professionnel</h3>
                <p>Accueil, pages À propos, Services et Contact avec un design cohérent, chaleureux et adapté à l'identité béninoise.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag">Comptes</span>
                <h3>Authentification sécurisée</h3>
                <p>Pages de connexion et d'inscription pour acheteurs et vendeurs, avec vérification email et protection contre les tentatives d'intrusion.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag">Catalogue</span>
                <h3>Valorisation des catégories</h3>
                <p>Textile, artisanat, agroalimentaire : des fiches produits propres et attractives pour mettre en valeur chaque offre locale.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag gold">Vendeurs</span>
                <h3>Espace vendeur dédié</h3>
                <p>Tableau de bord pour gérer les produits, suivre les commandes et visualiser les statistiques de vente en temps réel.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag gold">Commandes</span>
                <h3>Gestion des commandes</h3>
                <p>Panier, checkout et suivi des livraisons pour une expérience d'achat fluide côté client et vendeur.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag gold">Administration</span>
                <h3>Panel administrateur</h3>
                <p>Validation des vendeurs, approbation des produits, suivi des paiements et gestion des expéditions depuis un seul espace.</p>
            </div>
        </div>
    </div>

    {{-- Pourquoi nous choisir --}}
    <div class="inner-section">
        <p class="section-label">Notre différence</p>
        <h2 class="section-title">Pourquoi choisir PME Bénin ?</h2>
        <div class="grid-2">
            <div class="card" data-reveal>
                <h3>Conçu pour le contexte béninois</h3>
                <p>Chaque fonctionnalité est pensée pour les besoins réels des PME locales : paiement en XOF, support mobile, interface simple et accessible à tous les niveaux de maîtrise numérique.</p>
            </div>
            <div class="card" data-reveal>
                <h3>Sécurité et fiabilité</h3>
                <p>Protection contre la force brute, vérification des emails, tokens sécurisés, gestion fine des rôles (acheteur, vendeur, admin) et audit régulier du code.</p>
            </div>
        </div>
    </div>

</div>
@endsection