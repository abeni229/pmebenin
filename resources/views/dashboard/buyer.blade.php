@extends('layouts.app')

@section('title', 'Tableau de bord acheteur - PME Bénin')
@section('page-class', 'dashboard-page')

@section('content')
    <section class="ds-section" aria-label="Tableau de bord acheteur">
        <div class="ds-hero ds-card" data-reveal>
            <div class="ds-hero-grid">
                <div class="ds-hero-copy" data-reveal>
                    <span class="ds-badge">Acheteur</span>
                    <h1>Tableau de bord client</h1>
                    <p>Retrouve tes commandes récentes, ta liste de souhaits et ton activité dans la marketplace.</p>
                </div>
            </div>
        </div>

        <div class="ds-card-group three-cols">
            <article class="ds-feature-card" data-reveal>
                <strong>Commandes</strong>
                <h3>{{ number_format($orderCount) }}</h3>
                <p>Commandes passées depuis ton compte.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Wishlist</strong>
                <h3>{{ number_format($wishlistCount) }}</h3>
                <p>Produits ajoutés à ta liste de favoris.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Suivi de commande</strong>
                <h3>Voir le statut</h3>
                <p>Consulte l’avancement de la livraison et le paiement de chaque commande.</p>
                <a href="/orders" class="ds-button ds-button-secondary" style="margin-top: 0.75rem; display: inline-flex;">Voir mes commandes</a>
            </article>
        </div>

        <section class="ds-section" aria-label="Commandes récentes">
            <p class="ds-section-title">Commandes récentes</p>
            <div class="ds-card-group">
                @forelse($recentOrders as $order)
                    <article class="ds-card" data-reveal>
                        <h3>Commande #{{ $order->id }}</h3>
                        <p>Montant : <strong>{{ number_format($order->total_amount, 0, ',', ' ') }} {{ $order->currency }}</strong></p>
                        <p>Status : <strong>{{ ucfirst($order->status ?? 'en attente') }}</strong></p>
                    </article>
                @empty
                    <article class="ds-card" data-reveal>
                        <p>Aucune commande récente pour l’instant.</p>
                    </article>
                @endforelse
            </div>
        </section>
    </section>
@endsection
