@php
$pageSlides = [
    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1800&q=80',
    'https://images.unsplash.com/photo-1521334884684-d80222895322?q=80&w=1800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1598524374912-cf4a7eed3c08?q=80&w=1800&auto=format&fit=crop',
];
@endphp

@extends('layouts.app')
@section('title', 'À propos — PME Bénin')

@section('content')
<div class="content-pane" data-reveal>

    <div class="page-flash" style="background-image: url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1800&q=80');">
        <div class="page-flash-body">
            <p class="page-flash-kicker">À propos</p>
            <h1>Un projet pensé pour valoriser les talents et produits du Bénin.</h1>
            <p>Nous construisons une vitrine digitale qui raconte l'histoire des artisans, du textile et de l'agroalimentaire local.</p>
        </div>
    </div>

    <div class="inner-section">
        <p class="section-label">Notre mission</p>
        <h2 class="section-title">Pourquoi PME Bénin ?</h2>
        <p class="section-sub">Un site qui transforme l'identité locale en une présence digitale professionnelle, prête à accueillir acheteurs béninois et internationaux.</p>

        <div class="grid-3">
            <div class="card" data-reveal>
                <span class="card-tag">Identité locale</span>
                <h3>Mettre en valeur le savoir-faire béninois</h3>
                <p>Chaque produit et chaque vendeur bénéficie d'une présentation claire, authentique et respectueuse de son origine culturelle.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag">Image pro</span>
                <h3>Créer la confiance avant la marketplace</h3>
                <p>Une plateforme soignée rassure les visiteurs, donne de la légitimité au projet et facilite le passage à l'acte d'achat.</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag gold">Visibilité</span>
                <h3>Rassembler producteurs locaux et acheteurs internationaux</h3>
                <p>La plateforme aide à ouvrir les produits béninois au monde sans perdre le lien avec les racines.</p>
            </div>
        </div>
    </div>

    <div class="inner-section">
        <p class="section-label">Les chiffres</p>
        <h2 class="section-title">PME Bénin en quelques données</h2>
        <div class="grid-3">
            <div class="card" data-reveal style="text-align:center; padding:2.5rem;">
                <div style="font-family:var(--f-serif); font-size:3.5rem; font-weight:700; color:var(--green-2); line-height:1;">3</div>
                <p style="margin-top:0.6rem; font-weight:600; color:var(--ink);">Catégories de produits</p>
                <p>Textile, artisanat, agroalimentaire</p>
            </div>
            <div class="card" data-reveal style="text-align:center; padding:2.5rem;">
                <div style="font-family:var(--f-serif); font-size:3.5rem; font-weight:700; color:var(--green-2); line-height:1;">100%</div>
                <p style="margin-top:0.6rem; font-weight:600; color:var(--ink);">Local et authentique</p>
                <p>Chaque produit vient d'un artisan béninois vérifié</p>
            </div>
            <div class="card" data-reveal style="text-align:center; padding:2.5rem;">
                <div style="font-family:var(--f-serif); font-size:3.5rem; font-weight:700; color:var(--gold); line-height:1;">XOF</div>
                <p style="margin-top:0.6rem; font-weight:600; color:var(--ink);">Monnaie locale</p>
                <p>Transactions en franc CFA pour les PME béninoises</p>
            </div>
        </div>
    </div>

</div>
@endsection