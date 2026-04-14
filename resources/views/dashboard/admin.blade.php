@extends('layouts.app')

@section('title', 'Tableau de bord admin - PME Bénin')
@section('page-class', 'dashboard-page')

@section('content')
    <section class="ds-section" aria-label="Tableau de bord admin">
        <div class="ds-hero ds-card" data-reveal>
            <div class="ds-hero-grid">
                <div class="ds-hero-copy" data-reveal>
                    <span class="ds-badge">Admin</span>
                    <h1>Tableau de bord administrateur</h1>
                    <p>Surveille les vendeurs, valide les comptes et consulte les performances globales de la marketplace.</p>
                </div>
            </div>
        </div>

        <div class="ds-card-group three-cols">
            <article class="ds-feature-card" data-reveal>
                <strong>Utilisateurs</strong>
                <h3>{{ number_format($totalUsers) }} inscrits</h3>
                <p>{{ number_format($buyers) }} acheteurs, {{ number_format($sellers) }} artisans et {{ number_format($admins) }} administrateurs.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Vendeurs en attente</strong>
                <h3>{{ number_format($pendingSellers) }}</h3>
                <p>Vendeurs dont le profil doit encore être approuvé pour vendre sur la plateforme.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Catalogue</strong>
                <h3>{{ number_format($products) }} produits</h3>
                <p>Produits actifs publiés par les artisans et disponibles en boutique.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Commandes</strong>
                <h3>{{ number_format($orders) }}</h3>
                <p>Nombre total de commandes enregistrées sur la marketplace.</p>
            </article>
        </div>

        <section class="ds-section" aria-label="Vendeurs récents">
            <p class="ds-section-title">Vendeurs récents</p>
            <div class="ds-card-group">
                @forelse($recentSellers as $seller)
                    <article class="ds-card" data-reveal>
                        <h3>{{ $seller->name }}</h3>
                        <p>{{ $seller->email }}</p>
                        <p>Status : <strong>{{ ucfirst($seller->seller_status) }}</strong></p>
                    </article>
                @empty
                    <article class="ds-card" data-reveal>
                        <p>Aucun vendeur récent pour le moment.</p>
                    </article>
                @endforelse
            </div>
        </section>
    </section>
@endsection
