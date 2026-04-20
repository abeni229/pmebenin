@extends('layouts.app')

@section('title', 'Boutique - PME Bénin')
@section('page-class', 'shop-page')

@section('content')
    <section class="ds-section" aria-label="Boutique PME Bénin">
        <div class="ds-hero ds-card" data-reveal>
            <div class="ds-hero-grid">
                <div class="ds-hero-copy" data-reveal>
                    <span class="ds-badge">Boutique locale</span>
                    <h1>Explorez les meilleures créations béninoises.</h1>
                    <p>Un catalogue premium organisé autour du savoir-faire, des catégories haut de gamme et de la confiance acheteur.</p>
                    <div class="ds-hero-actions">
                        <a href="/register" class="ds-button ds-button-primary">Créer un compte</a>
                        <a href="/contact" class="ds-button ds-button-secondary">Nous contacter</a>
                    </div>
                </div>
                <div class="ds-hero-visual" data-reveal>
                    <img src="https://images.unsplash.com/photo-1655682621708-5e42174d59ce?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Visuel boutique" class="ds-hero-visual-img">
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('shop') }}" class="ds-search-panel" data-reveal>
            <input type="search" name="q" class="ds-search-input" placeholder="Rechercher un produit, une catégorie ou un artisan" aria-label="Recherche de produits" value="{{ request('q') }}">
            <div class="ds-filters" role="group" aria-label="Filtres de boutique">
                <a href="{{ route('shop') }}" class="ds-chip {{ request('category') ? '' : 'ds-chip-active' }}">Tous</a>
                @foreach($categories as $category)
                    <a href="{{ route('shop', array_merge(request()->except('page'), ['category' => $category->slug])) }}" class="ds-chip {{ request('category') === $category->slug ? 'ds-chip-active' : '' }}">{{ $category->name }}</a>
                @endforeach
            </div>
        </form>

        <div class="ds-shop-summary" data-reveal>
            <p>{{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }} trouvé{{ $products->total() > 1 ? 's' : '' }}</p>
        </div>

        <div class="ds-shop-grid">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="ds-card" data-reveal>
                    <p>Aucun produit trouvé pour ces filtres. Essayez une autre recherche ou sélectionnez une autre catégorie.</p>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="ds-pagination" data-reveal>
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </section>
@endsection
