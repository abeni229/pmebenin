@extends('layouts.app')
@section('title', 'Mon espace — PME Bénin')

@push('styles')
<style>
/* ── Shared dashboard tokens ──────────────────────── */
.db-wrap { display:flex; flex-direction:column; gap:2rem; }

.db-header {
    background: linear-gradient(135deg, var(--green) 0%, var(--green-2) 100%);
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
.db-header h1 {
    font-family:var(--f-serif);
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight:600; color:#fff; line-height:1.15; margin:0 0 .4rem;
}
.db-header-info > p { color:rgba(255,255,255,.70); font-size:.95rem; }
.db-header-cta { position:relative; z-index:1; display:flex; gap:.7rem; flex-wrap:wrap; }
.db-btn {
    display:inline-flex; align-items:center;
    padding:.65rem 1.35rem; border-radius:99px;
    font-size:.87rem; font-weight:600; font-family:var(--f-sans);
    border:1.5px solid transparent; cursor:pointer;
    transition:transform .2s, box-shadow .2s; text-decoration:none;
}
.db-btn:hover { transform:translateY(-1px); }
.db-btn-ghost { background:rgba(255,255,255,.12); color:#fff; border-color:rgba(255,255,255,.22); }
.db-btn-ghost:hover { background:rgba(255,255,255,.20); }
.db-btn-gold  { background:linear-gradient(135deg,var(--gold-xl),var(--gold-l)); color:var(--green); box-shadow:0 4px 16px rgba(184,134,11,.28); }

/* KPIs */
.kpi-grid { display:grid; gap:1.2rem; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); }
.kpi-card {
    background:var(--white); border-radius:var(--r-lg);
    border:1px solid rgba(30,58,47,.08);
    box-shadow:0 4px 20px rgba(15,31,24,.06);
    padding:1.6rem 1.8rem; display:flex; flex-direction:column; gap:.25rem;
    position:relative; overflow:hidden;
    transition:transform .22s, box-shadow .22s;
}
.kpi-card::before {
    content:''; position:absolute; top:0; left:0; width:3px; height:100%;
    background:linear-gradient(180deg,var(--green-2),var(--gold));
    border-radius:99px 0 0 99px;
}
.kpi-card:hover { transform:translateY(-3px); box-shadow:0 14px 38px rgba(15,31,24,.12); }
.kpi-label { font-size:.74rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--muted); }
.kpi-value { font-family:var(--f-serif); font-size:2.8rem; font-weight:700; line-height:1; color:var(--green); }
.kpi-desc  { font-size:.82rem; color:var(--muted); line-height:1.5; }
.kpi-link  { margin-top:.7rem; font-size:.81rem; font-weight:700; color:var(--green-2); text-decoration:none; display:inline-flex; align-items:center; gap:.25rem; }
.kpi-link::after { content:'→'; }
.kpi-link:hover { color:var(--gold); }

/* Section */
.db-section { display:flex; flex-direction:column; gap:1rem; }
.db-section-head { display:flex; align-items:baseline; justify-content:space-between; gap:1rem; }
.db-section-head h2 { font-family:var(--f-serif); font-size:1.45rem; font-weight:600; color:var(--ink); margin:0; }
.db-section-head a { font-size:.84rem; font-weight:600; color:var(--green-2); text-decoration:none; }
.db-section-head a:hover { color:var(--gold); }

/* Lignes commandes */
.order-row {
    background:var(--white); border-radius:var(--r-lg);
    border:1px solid rgba(30,58,47,.08);
    box-shadow:0 4px 18px rgba(15,31,24,.06);
    padding:1.2rem 1.8rem;
    display:flex; align-items:center; justify-content:space-between;
    gap:1.2rem; flex-wrap:wrap;
    transition:box-shadow .2s;
}
.order-row:hover { box-shadow:0 10px 32px rgba(15,31,24,.11); }
.order-id   { font-weight:700; color:var(--ink); font-size:.95rem; }
.order-date { font-size:.82rem; color:var(--muted); margin-top:.1rem; }
.order-amt  { font-family:var(--f-serif); font-size:1.25rem; font-weight:700; color:var(--green); }

.pill { display:inline-flex; padding:.28rem .85rem; border-radius:99px; font-size:.73rem; font-weight:700; }
.pill-pending   { background:#FEFCE8; color:#92400E; border:1px solid #FDE68A; }
.pill-confirmed { background:#EFF6FF; color:#1E40AF; border:1px solid #BFDBFE; }
.pill-shipped   { background:#F0F9FF; color:#0369A1; border:1px solid #BAE6FD; }
.pill-delivered { background:#F0FDF4; color:#166534; border:1px solid #BBF7D0; }
.pill-cancelled { background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; }

.empty-state {
    background:var(--white); border-radius:var(--r-lg);
    border:1px dashed rgba(30,58,47,.15);
    padding:3rem 2rem; text-align:center;
}
.empty-state p { color:var(--muted); margin:.4rem 0 1.2rem; }
</style>
@endpush

@section('content')
<div class="content-pane">
<div style="padding:2.5rem 2.5rem 3rem;">
<div class="db-wrap">

    {{-- En-tête --}}
    <div class="db-header">
        <div class="db-header-info">
            <p class="db-role-tag">Acheteur</p>
            <h1>Bonjour, {{ Auth::user()->name }}</h1>
            <p>Retrouvez vos commandes, vos favoris et votre activité.</p>
        </div>
        <div class="db-header-cta">
            <a href="{{ route('shop') }}" class="db-btn db-btn-ghost">Parcourir la boutique</a>
            <a href="/orders"            class="db-btn db-btn-gold">Mes commandes</a>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <span class="kpi-label">Commandes</span>
            <span class="kpi-value">{{ number_format($orderCount) }}</span>
            <span class="kpi-desc">Commandes passées depuis votre compte.</span>
            <a href="/orders" class="kpi-link">Voir tout</a>
        </div>
        <div class="kpi-card">
            <span class="kpi-label">Favoris</span>
            <span class="kpi-value">{{ number_format($wishlistCount) }}</span>
            <span class="kpi-desc">Produits ajoutés à votre liste de souhaits.</span>
            <a href="/wishlist" class="kpi-link">Voir la liste</a>
        </div>
        <div class="kpi-card">
            <span class="kpi-label">Panier</span>
            <span class="kpi-value">{{ count(session('cart', [])) }}</span>
            <span class="kpi-desc">Articles actuellement dans votre panier.</span>
            <a href="{{ route('cart') }}" class="kpi-link">Voir le panier</a>
        </div>
    </div>

    {{-- Commandes récentes --}}
    <div class="db-section">
        <div class="db-section-head">
            <h2>Commandes récentes</h2>
            <a href="/orders">Voir toutes</a>
        </div>

        @forelse($recentOrders as $order)
            @php
                $pill = match($order->status) {
                    'confirmed' => ['pill-confirmed','Confirmée'],
                    'shipped'   => ['pill-shipped',  'Expédiée'],
                    'delivered' => ['pill-delivered', 'Livrée'],
                    'cancelled' => ['pill-cancelled', 'Annulée'],
                    default     => ['pill-pending',   'En attente'],
                };
            @endphp
            <div class="order-row">
                <div>
                    <div class="order-id">Commande #{{ $order->id }}</div>
                    <div class="order-date">{{ $order->created_at->format('d/m/Y') }}</div>
                </div>
                <span class="pill {{ $pill[0] }}">{{ $pill[1] }}</span>
                <div class="order-amt">{{ number_format($order->total_amount, 0, ',', ' ') }} {{ $order->currency }}</div>
            </div>
        @empty
            <div class="empty-state">
                <div style="font-family:var(--f-serif);font-size:2.5rem;color:var(--muted);opacity:.35;margin-bottom:.5rem">◌</div>
                <p>Vous n'avez pas encore passé de commande.</p>
                <a href="{{ route('shop') }}" class="db-btn db-btn-gold" style="display:inline-flex">Découvrir la boutique</a>
            </div>
        @endforelse
    </div>

</div>
</div>
</div>
@endsection