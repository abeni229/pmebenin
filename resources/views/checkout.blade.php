@extends('layouts.app')

@section('title', 'Paiement - PME Bénin')
@section('page-class', 'checkout-page')

@section('content')
    <section class="ds-section" aria-label="Paiement de commande PME Bénin">
        <div class="ds-hero ds-card" data-reveal>
            <div class="ds-hero-grid">
                <div class="ds-hero-copy" data-reveal>
                    <span class="ds-badge">Paiement sécurisé</span>
                    <h1>Finalise ta commande en toute confiance.</h1>
                    <p>Choisis un mode de paiement sécurisé et confirme l'adresse de livraison.</p>
                </div>
            </div>
        </div>

        <div class="ds-card" data-reveal style="margin-top: 1.5rem; padding: 1.5rem;">
            <div class="ds-checkout-summary">
                <div>
                    <h2>Résumé de commande</h2>
                    <p>{{ count($cart) }} article(s) dans le panier.</p>
                </div>
                <div class="ds-total-amount">
                    <strong>Total</strong>
                    <span>{{ number_format($total, 0, ',', ' ') }} XOF</span>
                </div>
            </div>

            <div class="ds-checkout-items">
                @foreach($cart as $item)
                    <div class="ds-checkout-item">
                        <div class="ds-cart-item-image" style="background-image: url('{{ $item['image'] ?: 'https://images.pexels.com/photos/1181650/pexels-photo-1181650.jpeg?auto=compress&cs=tinysrgb&h=900&w=1200' }}');"></div>
                        <div>
                            <strong>{{ $item['name'] }}</strong>
                            <p>{{ $item['quantity'] }} × {{ number_format($item['price'], 0, ',', ' ') }} {{ $item['currency'] }}</p>
                        </div>
                        <div>{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} {{ $item['currency'] }}</div>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('checkout.place') }}" class="ds-checkout-form">
                @csrf

                <div class="ds-form-field wide">
                    <label for="shipping_address">Adresse de livraison</label>
                    <textarea id="shipping_address" name="shipping_address" rows="4" required placeholder="Adresse complète de livraison..."></textarea>
                </div>

                <div class="ds-form-field wide">
                    <label for="payment_method">Moyen de paiement</label>
                    <select id="payment_method" name="payment_method" required>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method }}">{{ ucfirst(str_replace('_', ' ', $method)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="ds-checkout-note">
                    <p>Paiement sécurisé par Mobile Money, carte bancaire ou PayPal. Les transactions sont traitées via un tunnel de paiement sécurisé.</p>
                </div>

                <button type="submit" class="ds-button ds-button-primary">Valider et payer</button>
            </form>
        </div>
    </section>
@endsection
