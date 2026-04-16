@extends('layouts.app')

@section('title', 'Tableau de bord vendeur - PME Bénin')
@section('page-class', 'dashboard-page')

@section('content')
    <section class="ds-section" aria-label="Tableau de bord vendeur">
        <div class="ds-hero ds-card" data-reveal>
            <div class="ds-hero-grid">
                <div class="ds-hero-copy" data-reveal>
                    <span class="ds-badge">Vendeur</span>
                    <h1>Tableau de bord artisan</h1>
                    <p>Gère tes produits, suis tes commandes et contrôle ton activité de vente locale.</p>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="ds-card" data-reveal style="margin-top: 1.5rem; padding: 1.2rem; border: 1px solid rgba(124, 207, 79, 0.25); background: rgba(236, 250, 229, 0.9);">
                {{ session('status') }}
            </div>
        @endif

        <div class="ds-card-group three-cols">
            <article class="ds-feature-card" data-reveal>
                <strong>Produits</strong>
                <h3>{{ number_format($productCount) }}</h3>
                <p>Produits actifs publiés dans la boutique.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Commandes reçues</strong>
                <h3>{{ number_format($orderCount) }}</h3>
                <p>Commandes enregistrées pour tes produits.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Commandes en attente</strong>
                <h3>{{ number_format($pendingOrders) }}</h3>
                <p>Commandes qui attendent traitement ou validation.</p>
            </article>
            <article class="ds-feature-card" data-reveal>
                <strong>Chiffre d'affaires</strong>
                <h3>{{ number_format($salesAmount, 0, ',', ' ') }} XOF</h3>
                <p>Montant total des ventes enregistrées.</p>
            </article>
        </div>

        <section class="ds-section" aria-label="Ajouter un produit">
            <p class="ds-section-title">Ajouter un produit</p>
            <div class="ds-card" data-reveal>
                <form action="/dashboard/products" method="POST" class="ds-form-fields ds-product-form">
                    @csrf
                    <div class="ds-form-note">
                        <p>Complète les informations du produit pour rendre la fiche claire, attractive et prête à la publication.</p>
                    </div>

                    <div class="ds-form-card">
                        <div class="ds-form-card-header">
                            <div>
                                <p class="ds-form-card-label">Informations produit</p>
                                <h4>Fiche produit</h4>
                            </div>
                        </div>

                        <div class="ds-form-grid">
                            <div class="ds-form-field half">
                                <label for="name">Nom du produit</label>
                                <input id="name" name="name" type="text" required>
                            </div>

                            <div class="ds-form-field half">
                                <label for="category_id">Catégorie</label>
                                <select id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="ds-form-field wide">
                                <label for="image">URL de l’image</label>
                                <input id="image" name="image" type="url" placeholder="https://...">
                            </div>

                            <div class="ds-form-field wide">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="5" placeholder="Description du produit..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="ds-form-card">
                        <div class="ds-form-card-header">
                            <div>
                                <p class="ds-form-card-label">Prix & stock</p>
                                <h4>Détails commerciaux</h4>
                            </div>
                        </div>

                        <div class="ds-form-grid">
                            <div class="ds-form-field third">
                                <label for="price">Prix</label>
                                <input id="price" name="price" type="number" min="0" step="100" required>
                            </div>

                            <div class="ds-form-field third">
                                <label for="stock">Stock</label>
                                <input id="stock" name="stock" type="number" min="0" required>
                            </div>

                            <div class="ds-form-field third">
                                <label for="currency">Devise</label>
                                <input id="currency" name="currency" type="text" value="XOF" required>
                            </div>
                        </div>
                    </div>

                    <div class="ds-form-actions">
                        <button type="submit" class="ds-button ds-button-primary">Ajouter le produit</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="ds-section" aria-label="Commandes vendeur">
            <p class="ds-section-title">Gestion des commandes</p>
            @forelse($orders as $order)
                <article class="ds-card" data-reveal style="margin-bottom: 1rem;">
                    <div style="display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                        <div>
                            <h3>Commande #{{ $order->id }}</h3>
                            <p>Acheteur : <strong>{{ $order->buyer?->name ?? 'N/A' }}</strong></p>
                            <p>Total : <strong>{{ number_format($order->total_amount, 0, ',', ' ') }} {{ $order->currency }}</strong></p>
                            <p>Status commande : <strong>{{ ucfirst($order->status) }}</strong></p>
                            <p>Status livraison : <strong>{{ ucfirst($order->shipping_status) }}</strong></p>
                        </div>
                        <div style="min-width: 220px;">
                            <form action="/dashboard/orders/{{ $order->id }}/status" method="POST" style="margin-bottom: 0.75rem;">
                                @csrf
                                @method('PATCH')
                                <label for="status-{{ $order->id }}">Mettre à jour le statut</label>
                                <select id="status-{{ $order->id }}" name="status">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="ds-button ds-button-secondary">Valider</button>
                            </form>
                            <form action="/dashboard/orders/{{ $order->id }}/shipping" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="shipping_status-{{ $order->id }}">État livraison</label>
                                <select id="shipping_status-{{ $order->id }}" name="shipping_status">
                                    <option value="pending" {{ $order->shipping_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="preparing" {{ $order->shipping_status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                    <option value="shipped" {{ $order->shipping_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->shipping_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->shipping_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="ds-button ds-button-primary">Mettre à jour</button>
                            </form>
                        </div>
                    </div>

                    <details style="margin-top:1rem;">
                        <summary>Articles de la commande</summary>
                        <ul>
                            @foreach($order->items as $item)
                                <li>{{ $item->product?->name ?? 'Produit supprimé' }} × {{ $item->quantity }} — {{ number_format($item->total_price, 0, ',', ' ') }} XOF</li>
                            @endforeach
                        </ul>
                    </details>
                </article>
            @empty
                <article class="ds-card" data-reveal>
                    <p>Aucune commande à gérer pour le moment.</p>
                </article>
            @endforelse
        </section>
    </section>
@endsection
