@php
$pageSlides = [
    'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1800&q=80',
    'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&w=1800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1556761175-5973dc0f32d7?q=80&w=1800&auto=format&fit=crop',
];
@endphp

@extends('layouts.app')
@section('title', 'Contact — PME Bénin')

@section('content')
<div class="content-pane" data-reveal>

    <div class="page-flash" style="background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1800&q=80');">
        <div class="page-flash-body">
            <p class="page-flash-kicker">Contact</p>
            <h1>Entrer en contact pour lancer votre projet marketplace.</h1>
            <p>Nous sommes disponibles pour discuter de votre idée et trouver la meilleure solution pour votre activité.</p>
        </div>
    </div>

    <div class="inner-section">
        <p class="section-label">Contactez-nous</p>
        <h2 class="section-title">Parlons de votre projet</h2>
        <p class="section-sub">Nous répondons rapidement aux demandes de création de site, marketplace et accompagnement vendeur.</p>

        <div class="grid-2">
            <div class="card" data-reveal>
                <span class="card-tag">Équipe projet</span>
                <h3>Coordonnées directes</h3>
                <p>
                    Email : <strong style="color:var(--ink)">contact@pmebenin.bj</strong><br>
                    Téléphone : <strong style="color:var(--ink)">+229 01 50 43 47 10</strong>
                </p>
                <p style="margin-top:1rem">Disponibles du lundi au vendredi, 8h – 18h (heure de Cotonou).</p>
            </div>
            <div class="card" data-reveal>
                <span class="card-tag gold">Message</span>
                <h3>Décrivez votre projet</h3>
                <p>Mentionnez les produits que vous souhaitez vendre, votre localisation, et vos contraintes. Nous vous proposerons une solution adaptée à votre réalité de terrain.</p>
            </div>
        </div>
    </div>

</div>
@endsection