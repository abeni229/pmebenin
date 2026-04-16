@extends('layouts.app')

@section('title', 'Panier - PME Bénin')
@section('page-class', 'cart-page')

@section('content')
    <section class="ds-section" aria-label="Panier PME Bénin">
        <div class="ds-hero ds-card" data-reveal>
            <div class="ds-hero-grid">
                <div class="ds-hero-copy" data-reveal>
                    <span class="ds-badge">Mon panier</span>
                    <h1>Vérifie ton panier avant de finaliser ta commande.</h1>
                    <p>Ajoute, mets à jour ou supprime des articles avant de choisir ton mode de paiement sécurisé.</p>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="ds-card" data-reveal style="margin-top: 1.5rem; padding: 1.2rem; border: 1px solid rgba(124, 207, 79, 0.25); background: rgba(236, 250, 229, 0.9);">
                {{ session('status') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="ds-card" data-reveal style="margin-top: 1.5rem;">
                <h2>Ton panier est vide</h2>
                <p>Découvre nos catégories artisanat, textile et agroalimentaire pour trouver un produit local.</p>
                <a href="{{ route('shop') }}" class="ds-button ds-button-primary">Retour à la boutique</a>
            </div>
        @else
            <div class="ds-card" data-reveal style="margin-top: 1.5rem; padding: 1.5rem;">
                <form method="POST" action="{{ route('cart.update') }}">
                    @csrf
                    <table class="ds-cart-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Sous-total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                                <tr class="ds-cart-table-row">
                                    <td data-label="Produit">
                                        <div class="ds-cart-item">
                                            <div class="ds-cart-item-image" style="background-image: url('{{ $item['image'] ?: 'https://images.pexels.com/photos/1181650/pexels-photo-1181650.jpeg?auto=compress&cs=tinysrgb&h=900&w=1200' }}');"></div>
                                            <div>
                                                <strong>{{ $item['name'] }}</strong>
                                                <p>{{ $item['seller_name'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Catégorie">{{ $item['category'] }}</td>
                                    <td data-label="Quantité">
                                        <input type="number" name="items[{{ $item['id'] }}][quantity]" value="{{ $item['quantity'] }}" min="1" class="ds-input-number">
                                    </td>
                                    <td data-label="Prix unitaire">{{ number_format($item['price'], 0, ',', ' ') }} {{ $item['currency'] }}</td>
                                    <td data-label="Sous-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} {{ $item['currency'] }}</td>
                                    <td data-label="">
                                        <form method="POST" action="{{ route('cart.remove', ['product' => $item['id']]) }}">
                                            @csrf
                                            <button type="submit" class="ds-button ds-button-secondary">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="ds-cart-actions">
                        <button type="submit" class="ds-button ds-button-secondary">Mettre à jour le panier</button>
                        <a href="{{ route('checkout') }}" class="ds-button ds-button-primary">Passer à la caisse</a>
                    </div>
                </form>

                <div class="ds-cart-total">
                    <strong>Total du panier</strong>
                    <span>{{ number_format($total, 0, ',', ' ') }} XOF</span>
                </div>
            </div>
        @endif
    </section>
@endsection
