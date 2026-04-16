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
                <strong>Produits à vérifier</strong>
                <h3>{{ number_format($pendingProducts) }}</h3>
                <p>Produits en attente de validation qualité avant publication.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Commandes</strong>
                <h3>{{ number_format($orders) }}</h3>
                <p>Nombre total de commandes enregistrées sur la marketplace.</p>
            </article>
        </div>

        <div class="ds-card-group three-cols">
            <article class="ds-feature-card" data-reveal>
                <strong>Commissions</strong>
                <h3>{{ number_format($totalCommission, 2, ',', ' ') }} XOF</h3>
                <p>Revenus estimés générés par les commissions de paiement.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Expéditions en cours</strong>
                <h3>{{ number_format($pendingShipments) }}</h3>
                <p>Envois qui nécessitent un suivi ou une mise à jour logistique.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Catalogue</strong>
                <h3>{{ number_format($products) }} produits</h3>
                <p>Produits actifs publiés par les artisans et disponibles en boutique.</p>
            </article>
        </div>

        <section class="ds-section" aria-label="Vendeurs récents">
            <div class="ds-section-header">
                <p class="ds-section-title">Vendeurs récents</p>
                <p class="ds-section-subtitle">Suivi clair des comptes vendeurs et de leur statut d'approbation.</p>
            </div>

            <div class="ds-card" data-reveal>
                <table class="ds-cart-table ds-admin-seller-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSellers as $seller)
                            <tr>
                                <td data-label="Nom"><strong>{{ $seller->name }}</strong></td>
                                <td data-label="Email"><a href="mailto:{{ $seller->email }}">{{ $seller->email }}</a></td>
                                <td data-label="Statut"><span class="ds-badge">{{ $seller->seller_status_label }}</span></td>
                                <td data-label="Inscription">{{ $seller->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Aucun vendeur récent pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
