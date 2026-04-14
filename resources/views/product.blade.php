@extends('layouts.app')

@section('title', $product->name . ' - PME Bénin')
@section('page-class', 'product-page')

@section('content')
    <section class="ds-section" aria-label="Fiche produit principale">
        <div class="ds-product-detail" data-reveal>
            <div class="ds-product-detail-grid">
                <div class="ds-product-hero ds-card">
                    <div class="ds-product-image-large" style="background-image: url('{{ $product->image ?: 'https://images.unsplash.com/photo-1540574163026-643ea20ade25?auto=format&fit=crop&w=1200&q=80' }}');"></div>
                    <div class="ds-product-info">
                        <span class="ds-badge">{{ $product->category->name ?? 'Produit local' }}</span>
                        <h1>{{ $product->name }}</h1>
                        <p>{{ $product->description ?: 'Description en attente du vendeur.' }}</p>
                        @php $rating = $product->reviews->avg('rating'); @endphp
                        <div class="ds-product-meta-list">
                            <div class="ds-product-meta-item"><span>Vendeur</span><strong>{{ $product->seller->name ?? 'Vendeur local' }}</strong></div>
                            <div class="ds-product-meta-item"><span>Note</span><strong>{{ $rating ? number_format($rating, 1) . '/5' : 'Pas encore d\'avis' }}</strong></div>
                            <div class="ds-product-meta-item"><span>Stock</span><strong>{{ $product->stock }}</strong></div>
                        </div>
                    </div>
                </div>

                <aside class="ds-product-panel ds-card">
                    <strong>Résumé de l’offre</strong>
                    <div class="ds-product-price-large">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->currency }}</div>
                    <p>{{ $product->description ? \Illuminate\Support\Str::limit($product->description, 120) : 'Un produit artisanal de qualité disponible sur PME Bénin.' }}</p>
                    <div class="ds-product-cta">
                        <a href="/cart" class="ds-button ds-button-primary">Ajouter au panier</a>
                        <a href="/contact" class="ds-button ds-button-secondary">Contacter le vendeur</a>
                    </div>
                    <div class="ds-product-detail-keys">
                        <div><strong>Catégorie</strong> : {{ $product->category->name ?? 'Autre' }}</div>
                        <div><strong>Devise</strong> : {{ $product->currency }}</div>
                        <div><strong>Disponibilité</strong> : {{ $product->stock > 0 ? 'En stock' : 'Rupture' }}</div>
                    </div>
                </aside>
            </div>

            <div class="ds-product-tabs ds-card">
                <div class="ds-tabs-list">
                    <div class="ds-tab active">Description</div>
                    <div class="ds-tab">Détails</div>
                    <div class="ds-tab">Avis</div>
                </div>

                <div>
                    <h2>Description</h2>
                    <p>{{ $product->description ?: 'Aucune description supplémentaire pour ce produit.' }}</p>
                </div>

                <div style="margin-top: 1.5rem;">
                    <h2>Détails du produit</h2>
                    <ul class="ds-product-detail-keys">
                        <li>Catégorie : {{ $product->category->name ?? 'N/A' }}</li>
                        <li>Stock : {{ $product->stock }}</li>
                        <li>Devise : {{ $product->currency }}</li>
                        <li>Vendeur : {{ $product->seller->name ?? 'Vendeur local' }}</li>
                    </ul>
                </div>

                <div style="margin-top: 1.5rem;">
                    <h2>Avis</h2>
                    @if($product->reviews->isEmpty())
                        <p>Aucun avis pour le moment. Soyez le premier à partager votre expérience.</p>
                    @else
                        @foreach($product->reviews as $review)
                            <div class="ds-review-card">
                                <strong>{{ $review->user->name ?? 'Anonyme' }}</strong>
                                <p>{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
