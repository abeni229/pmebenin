@extends('layouts.app')
@section('title', 'Tableau de bord vendeur — PME Bénin')

@push('styles')
<style>
/* ── Shared avec buyer (tokens déjà définis si inclus ensemble) ── */
.db-wrap { display:flex; flex-direction:column; gap:2rem; }

.db-header {
    background: linear-gradient(135deg, #1a3828 0%, var(--green-2) 100%);
    border-radius: var(--r-xl);
    padding: 2.5rem 3rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: 1.5rem; flex-wrap: wrap;
    position: relative; overflow: hidden;
}
.db-header::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
}
.db-header-info { position:relative; z-index:1; }
.db-role-tag {
    font-size:.71rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.18em; color:var(--gold-xl); margin-bottom:.5rem;
    display:flex; align-items:center; gap:.5rem;
}
.db-role-tag::before { content:''; width:1.2rem; height:1px; background:var(--gold-xl); display:block; }
.db-header h1 { font-family:var(--f-serif); font-size:clamp(1.8rem,3vw,2.4rem); font-weight:600; color:#fff; line-height:1.15; margin:0 0 .4rem; }
.db-header-info > p { color:rgba(255,255,255,.70); font-size:.95rem; }
.db-header-cta { position:relative; z-index:1; display:flex; gap:.7rem; flex-wrap:wrap; }
.db-btn {
    display:inline-flex; align-items:center; padding:.65rem 1.35rem; border-radius:99px;
    font-size:.87rem; font-weight:600; font-family:var(--f-sans);
    border:1.5px solid transparent; cursor:pointer;
    transition:transform .2s, box-shadow .2s; text-decoration:none;
}
.db-btn:hover { transform:translateY(-1px); }
.db-btn-ghost { background:rgba(255,255,255,.12); color:#fff; border-color:rgba(255,255,255,.22); }
.db-btn-ghost:hover { background:rgba(255,255,255,.20); }
.db-btn-gold  { background:linear-gradient(135deg,var(--gold-xl),var(--gold-l)); color:var(--green); box-shadow:0 4px 16px rgba(184,134,11,.28); }
.db-btn-green { background:linear-gradient(135deg,var(--green-2),var(--green)); color:#fff; box-shadow:0 4px 16px rgba(30,58,47,.28); }
.db-btn-outline { background:transparent; color:var(--green-2); border-color:rgba(30,58,47,.25); }
.db-btn-outline:hover { background:var(--sand); }
.db-btn-sm { padding:.45rem 1rem; font-size:.8rem; }

/* KPIs */
.kpi-grid { display:grid; gap:1.2rem; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); }
.kpi-card {
    background:var(--white); border-radius:var(--r-lg);
    border:1px solid rgba(30,58,47,.08);
    box-shadow:0 4px 20px rgba(15,31,24,.06);
    padding:1.5rem 1.8rem; display:flex; flex-direction:column; gap:.2rem;
    position:relative; overflow:hidden;
    transition:transform .22s, box-shadow .22s;
}
.kpi-card::before {
    content:''; position:absolute; top:0; left:0; width:3px; height:100%;
    background:linear-gradient(180deg,var(--green-2),var(--gold));
    border-radius:99px 0 0 99px;
}
.kpi-card:hover { transform:translateY(-3px); box-shadow:0 14px 38px rgba(15,31,24,.12); }
.kpi-label { font-size:.73rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--muted); }
.kpi-value { font-family:var(--f-serif); font-size:2.6rem; font-weight:700; line-height:1; color:var(--green); }
.kpi-value.gold { color:var(--gold); }
.kpi-desc  { font-size:.81rem; color:var(--muted); line-height:1.5; }

/* Section */
.db-section { display:flex; flex-direction:column; gap:1.2rem; }
.db-section-head { display:flex; align-items:baseline; justify-content:space-between; gap:1rem; }
.db-section-head h2 { font-family:var(--f-serif); font-size:1.4rem; font-weight:600; color:var(--ink); margin:0; }

/* Formulaire ajout produit */
.form-pane {
    background:var(--white); border-radius:var(--r-lg);
    border:1px solid rgba(30,58,47,.08);
    box-shadow:0 4px 20px rgba(15,31,24,.06);
    overflow:hidden;
}
.form-pane-header {
    padding:1.4rem 1.8rem; border-bottom:1px solid rgba(30,58,47,.07);
    display:flex; align-items:center; justify-content:space-between; gap:1rem;
}
.form-pane-header h3 { font-family:var(--f-serif); font-size:1.2rem; font-weight:600; color:var(--ink); margin:0; }
.form-pane-header p  { font-size:.83rem; color:var(--muted); margin:0; }
.form-pane-body { padding:1.8rem; display:flex; flex-direction:column; gap:1.5rem; }

.form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; }
.form-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.2rem; }
@media(max-width:680px){
    .form-grid-2,.form-grid-3 { grid-template-columns:1fr; }
}
.form-field { display:flex; flex-direction:column; gap:.4rem; }
.form-field.full { grid-column:1/-1; }
.form-field label { font-size:.84rem; font-weight:600; color:var(--ink); }
.form-field input,
.form-field select,
.form-field textarea {
    background:var(--sand); border:1.5px solid transparent;
    border-radius:var(--r-md); padding:.85rem 1rem;
    font-size:.94rem; font-family:var(--f-sans); color:var(--ink);
    transition:border-color .22s, background .22s, box-shadow .22s;
    width:100%;
}
.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
    outline:none; background:var(--white);
    border-color:var(--green-2);
    box-shadow:0 0 0 3px rgba(45,82,66,.10);
}
.form-field textarea { resize:vertical; min-height:110px; }
.form-note { font-size:.78rem; color:var(--muted); margin-top:.2rem; }
.form-divider { border:none; border-top:1px solid rgba(30,58,47,.08); margin:0; }
.form-section-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.14em; color:var(--gold); margin-bottom:-.4rem; }
.form-actions { display:flex; justify-content:flex-end; gap:.8rem; padding-top:.5rem; }

/* Preview image */
.img-preview {
    margin-top:.7rem; border-radius:var(--r-md);
    border:1px solid rgba(30,58,47,.10);
    overflow:hidden; display:none;
}
.img-preview img { display:block; width:100%; max-height:220px; object-fit:cover; }

/* Commandes */
.order-card {
    background:var(--white); border-radius:var(--r-lg);
    border:1px solid rgba(30,58,47,.08);
    box-shadow:0 4px 18px rgba(15,31,24,.06);
    overflow:hidden;
    transition:box-shadow .2s;
}
.order-card:hover { box-shadow:0 10px 32px rgba(15,31,24,.11); }
.order-card-head {
    padding:1.2rem 1.8rem; border-bottom:1px solid rgba(30,58,47,.07);
    display:flex; align-items:center; justify-content:space-between;
    gap:1rem; flex-wrap:wrap;
}
.order-id   { font-weight:700; color:var(--ink); font-size:.95rem; }
.order-buyer{ font-size:.82rem; color:var(--muted); margin-top:.1rem; }
.order-amt  { font-family:var(--f-serif); font-size:1.2rem; font-weight:700; color:var(--green); }
.order-card-body { padding:1.4rem 1.8rem; display:flex; gap:2rem; flex-wrap:wrap; }
.order-forms { display:flex; flex-direction:column; gap:1rem; flex:1; min-width:220px; }
.order-forms label { font-size:.82rem; font-weight:600; color:var(--ink); margin-bottom:.3rem; display:block; }
.order-forms select {
    width:100%; background:var(--sand); border:1.5px solid transparent;
    border-radius:var(--r-md); padding:.7rem .9rem;
    font-size:.88rem; font-family:var(--f-sans); color:var(--ink);
    transition:border-color .2s;
}
.order-forms select:focus { outline:none; border-color:var(--green-2); }
.order-forms-actions { display:flex; gap:.6rem; margin-top:.4rem; }

.pill { display:inline-flex; padding:.28rem .85rem; border-radius:99px; font-size:.73rem; font-weight:700; }
.pill-pending   { background:#FEFCE8; color:#92400E; border:1px solid #FDE68A; }
.pill-confirmed { background:#EFF6FF; color:#1E40AF; border:1px solid #BFDBFE; }
.pill-shipped   { background:#F0F9FF; color:#0369A1; border:1px solid #BAE6FD; }
.pill-delivered { background:#F0FDF4; color:#166534; border:1px solid #BBF7D0; }
.pill-cancelled { background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; }

/* Articles détails */
.order-items-toggle {
    padding:.75rem 1.8rem; background:var(--sand);
    border:none; width:100%; text-align:left;
    font-size:.84rem; font-weight:600; color:var(--green-2);
    cursor:pointer; font-family:var(--f-sans);
    display:flex; align-items:center; gap:.4rem;
    border-top:1px solid rgba(30,58,47,.07);
}
.order-items-toggle:hover { background:var(--sand-2); }
.order-items-body { padding:1rem 1.8rem 1.4rem; display:none; }
.order-items-body.open { display:block; }
.order-item-line {
    display:flex; justify-content:space-between; align-items:center;
    padding:.55rem 0; border-bottom:1px solid rgba(30,58,47,.06);
    font-size:.88rem; color:var(--ink);
}
.order-item-line:last-child { border-bottom:none; }
.order-item-qty { color:var(--muted); font-size:.8rem; }
.order-item-price { font-weight:700; color:var(--green); }

.empty-state {
    background:var(--white); border-radius:var(--r-lg);
    border:1px dashed rgba(30,58,47,.15);
    padding:3rem 2rem; text-align:center;
}
.empty-state p { color:var(--muted); margin:.4rem 0 1.2rem; }

/* Statut vendeur pending */
.seller-warning {
    background:linear-gradient(135deg,#FFFBEB,#FEF3C7);
    border:1px solid #FDE68A; border-radius:var(--r-lg);
    padding:1.4rem 1.8rem;
    display:flex; gap:1rem; align-items:flex-start;
}
.seller-warning-icon { font-size:1.4rem; flex-shrink:0; }
.seller-warning h3 { font-size:.95rem; font-weight:700; color:#92400E; margin:0 0 .3rem; }
.seller-warning p  { font-size:.87rem; color:#78350F; margin:0; line-height:1.6; }
</style>
@endpush

@section('content')
<div class="content-pane">
<div style="padding:2.5rem 2.5rem 3rem;">
<div class="db-wrap">

    {{-- En-tête --}}
    <div class="db-header">
        <div class="db-header-info">
            <p class="db-role-tag">Vendeur</p>
            <h1>Bonjour, {{ Auth::user()->name }}</h1>
            <p>Gérez vos produits, suivez vos commandes et contrôlez votre activité.</p>
        </div>
        <div class="db-header-cta">
            <a href="{{ route('shop') }}" class="db-btn db-btn-ghost">Voir la boutique</a>
        </div>
    </div>

    {{-- Avertissement si vendeur non approuvé --}}
    @if(Auth::user()->seller_status === 'pending')
    <div class="seller-warning">
        <span class="seller-warning-icon">⏳</span>
        <div>
            <h3>Compte en attente d'approbation</h3>
            <p>Votre compte vendeur est en cours de validation par notre équipe. Vous pourrez publier vos produits dès son approbation.</p>
        </div>
    </div>
    @endif

    @if(session('status'))
        <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:var(--r-md);padding:.9rem 1.2rem;color:#166534;font-size:.9rem;font-weight:500;">
            {{ session('status') }}
        </div>
    @endif

    {{-- KPIs --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <span class="kpi-label">Produits publiés</span>
            <span class="kpi-value">{{ number_format($productCount) }}</span>
            <span class="kpi-desc">Produits actifs dans la boutique.</span>
        </div>
        <div class="kpi-card">
            <span class="kpi-label">Commandes reçues</span>
            <span class="kpi-value">{{ number_format($orderCount) }}</span>
            <span class="kpi-desc">Total des commandes sur vos produits.</span>
        </div>
        <div class="kpi-card">
            <span class="kpi-label">En attente</span>
            <span class="kpi-value">{{ number_format($pendingOrders) }}</span>
            <span class="kpi-desc">Commandes à traiter ou valider.</span>
        </div>
        <div class="kpi-card">
            <span class="kpi-label">Chiffre d'affaires</span>
            <span class="kpi-value gold">{{ number_format($salesAmount, 0, ',', ' ') }}</span>
            <span class="kpi-desc">XOF — montant total des ventes.</span>
        </div>
    </div>

    {{-- ── Ajouter un produit ── --}}
    <div class="db-section">
        <div class="db-section-head">
            <h2>Ajouter un produit</h2>
        </div>

        <form action="/dashboard/products" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-pane">
                <div class="form-pane-header">
                    <div>
                        <h3>Nouvelle fiche produit</h3>
                        <p>Remplissez les informations pour publier votre produit dans la boutique.</p>
                    </div>
                </div>
                <div class="form-pane-body">

                    <p class="form-section-label">Informations produit</p>
                    <div class="form-grid-2">
                        <div class="form-field">
                            <label for="name">Nom du produit</label>
                            <input id="name" name="name" type="text" required placeholder="Ex : Pagne wax artisan">
                        </div>
                        <div class="form-field">
                            <label for="category_id">Catégorie</label>
                            <select id="category_id" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field full">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Décrivez votre produit en détail…"></textarea>
                        </div>
                        <div class="form-field full">
                            <label for="image">Image du produit</label>
                            <input id="image" name="image" type="file" accept="image/*">
                            <p class="form-note">Sélectionnez une photo depuis votre galerie ou appareil photo.</p>
                            <div class="img-preview" id="image-preview">
                                <img id="image-preview-img" src="" alt="Prévisualisation">
                            </div>
                        </div>
                    </div>

                    <hr class="form-divider">
                    <p class="form-section-label">Prix et stock</p>
                    <div class="form-grid-3">
                        <div class="form-field">
                            <label for="price">Prix</label>
                            <input id="price" name="price" type="number" min="0" step="100" required placeholder="Ex : 15000">
                        </div>
                        <div class="form-field">
                            <label for="stock">Stock disponible</label>
                            <input id="stock" name="stock" type="number" min="0" required placeholder="Ex : 10">
                        </div>
                        <div class="form-field">
                            <label for="currency">Devise</label>
                            <input id="currency" name="currency" type="text" value="XOF" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="db-btn db-btn-outline">Réinitialiser</button>
                        <button type="submit" class="db-btn db-btn-green">Publier le produit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Gestion des commandes ── --}}
    <div class="db-section">
        <div class="db-section-head">
            <h2>Gestion des commandes</h2>
        </div>

        @forelse($orders as $order)
            @php
                $sClass = match($order->status) {
                    'confirmed' => 'pill-confirmed', 'shipped' => 'pill-shipped',
                    'delivered' => 'pill-delivered', 'cancelled' => 'pill-cancelled',
                    default     => 'pill-pending',
                };
                $sLabel = match($order->status) {
                    'confirmed' => 'Confirmée', 'shipped' => 'Expédiée',
                    'delivered' => 'Livrée',    'cancelled' => 'Annulée',
                    default     => 'En attente',
                };
            @endphp
            <div class="order-card">
                <div class="order-card-head">
                    <div>
                        <div class="order-id">Commande #{{ $order->id }}</div>
                        <div class="order-buyer">Acheteur : {{ $order->buyer?->name ?? 'N/A' }}</div>
                    </div>
                    <span class="pill {{ $sClass }}">{{ $sLabel }}</span>
                    <div class="order-amt">{{ number_format($order->total_amount, 0, ',', ' ') }} {{ $order->currency }}</div>
                </div>

                <div class="order-card-body">
                    {{-- Statut commande --}}
                    <div class="order-forms">
                        <form action="/dashboard/orders/{{ $order->id }}/status" method="POST">
                            @csrf @method('PATCH')
                            <label for="status-{{ $order->id }}">Statut de la commande</label>
                            <select id="status-{{ $order->id }}" name="status">
                                @foreach(['pending'=>'En attente','confirmed'=>'Confirmée','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            <div class="order-forms-actions">
                                <button type="submit" class="db-btn db-btn-green db-btn-sm">Mettre à jour</button>
                            </div>
                        </form>
                    </div>

                    {{-- Statut livraison --}}
                    <div class="order-forms">
                        <form action="/dashboard/orders/{{ $order->id }}/shipping" method="POST">
                            @csrf @method('PATCH')
                            <label for="shipping_status-{{ $order->id }}">État de la livraison</label>
                            <select id="shipping_status-{{ $order->id }}" name="shipping_status">
                                @foreach(['pending'=>'En attente','preparing'=>'En préparation','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ $order->shipping_status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            <div class="order-forms-actions">
                                <button type="submit" class="db-btn db-btn-outline db-btn-sm">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Articles --}}
                <button type="button" class="order-items-toggle" onclick="this.nextElementSibling.classList.toggle('open'); this.textContent = this.nextElementSibling.classList.contains('open') ? '▲  Masquer les articles' : '▼  Voir les articles ({{ $order->items->count() }})'">
                    ▼ &nbsp;Voir les articles ({{ $order->items->count() }})
                </button>
                <div class="order-items-body">
                    @foreach($order->items as $item)
                        <div class="order-item-line">
                            <span>{{ $item->product?->name ?? 'Produit supprimé' }}</span>
                            <span class="order-item-qty">× {{ $item->quantity }}</span>
                            <span class="order-item-price">{{ number_format($item->total_price, 0, ',', ' ') }} XOF</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div style="font-family:var(--f-serif);font-size:2.5rem;color:var(--muted);opacity:.35;margin-bottom:.5rem">◌</div>
                <p>Aucune commande à gérer pour le moment.</p>
            </div>
        @endforelse
    </div>

</div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input   = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const img     = document.getElementById('image-preview-img');
    if (!input) return;
    input.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file || !file.type.startsWith('image/')) {
            preview.style.display = 'none'; img.src = ''; return;
        }
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush