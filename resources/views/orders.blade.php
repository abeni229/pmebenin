@extends('layouts.app')

@section('title', 'Mes commandes - PME Bénin')
@section('page-class', 'orders-page')

@section('content')
    <section class="ds-section" aria-label="Suivi des commandes PME Bénin">
        <div class="ds-hero ds-card" data-reveal>
            <div class="ds-hero-grid">
                <div class="ds-hero-copy" data-reveal>
                    <span class="ds-badge">Suivi de commande</span>
                    <h1>Toutes tes commandes et leur statut de livraison.</h1>
                    <p>Consulte l’avancement de chaque commande, le paiement et l’expédition en temps réel.</p>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="ds-card" data-reveal style="margin-top: 1.5rem; padding: 1.2rem; border: 1px solid rgba(124, 207, 79, 0.25); background: rgba(236, 250, 229, 0.9);">
                {{ session('status') }}
            </div>
        @endif

        <div class="ds-card-group" style="margin-top: 1.5rem;">
            @forelse($orders as $order)
                <article class="ds-card" data-reveal>
                    <div class="ds-order-header">
                        <div>
                            <h3>Commande #{{ $order->id }}</h3>
                            <p>Passée le {{ $order->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="ds-order-status">
                            <span>{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>

                    <div class="ds-order-details">
                        <p><strong>Montant</strong> : {{ number_format($order->total_amount, 0, ',', ' ') }} {{ $order->currency }}</p>
                        <p><strong>Paiement</strong> : {{ ucfirst(str_replace('_', ' ', $order->payment?->method ?? 'N/A')) }} ({{ ucfirst($order->payment?->status ?? 'en attente') }})</p>
                        <p><strong>Livraison</strong> : {{ ucfirst($order->shipping_status) }}</p>
                        <p><strong>Adresse</strong> : {{ $order->shipping_address }}</p>
                    </div>

                    <div class="ds-order-items">
                        <strong>Articles</strong>
                        <ul>
                            @foreach($order->items as $item)
                                <li>{{ $item->product?->name ?? 'Produit supprimé' }} × {{ $item->quantity }} — {{ number_format($item->total_price, 0, ',', ' ') }} XOF</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="ds-order-progress">
                        <strong>Suivi</strong>
                        <p>Votre commande est actuellement en <strong>{{ ucfirst($order->shipping_status) }}</strong>. Nous vous préviendrons dès que le statut changera.</p>
                    </div>
                </article>
            @empty
                <article class="ds-card" data-reveal>
                    <p>Aucune commande n’a encore été passée depuis ce compte.</p>
                    <a href="{{ route('shop') }}" class="ds-button ds-button-primary">Voir la boutique</a>
                </article>
            @endforelse
        </div>
    </section>
@endsection
