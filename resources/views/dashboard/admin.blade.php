@extends('layouts.app')
@section('title', 'Administration — PME Bénin')

@push('styles')
<style>
.db-wrap { display:flex; flex-direction:column; gap:2rem; }

/* En-tête admin — couleur distincte */
.db-header {
    background: linear-gradient(135deg, #0F1F18 0%, #1E3A2F 60%, #2a4a38 100%);
    border-radius: var(--r-xl); padding: 2.5rem 3rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: 1.5rem; flex-wrap: wrap; position: relative; overflow: hidden;
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
.db-header-info > p { color:rgba(255,255,255,.65); font-size:.95rem; }

/* Boutons */
.db-btn {
    display:inline-flex; align-items:center; padding:.65rem 1.35rem; border-radius:99px;
    font-size:.87rem; font-weight:600; font-family:var(--f-sans);
    border:1.5px solid transparent; cursor:pointer;
    transition:transform .2s, box-shadow .2s; text-decoration:none;
}
.db-btn:hover { transform:translateY(-1px); }
.db-btn-sm  { padding:.38rem .9rem; font-size:.78rem; }
.db-btn-green   { background:linear-gradient(135deg,var(--green-2),var(--green)); color:#fff; box-shadow:0 4px 14px rgba(30,58,47,.28); }
.db-btn-outline { background:transparent; color:var(--green-2); border-color:rgba(30,58,47,.25); }
.db-btn-outline:hover { background:var(--sand); }
.db-btn-danger  { background:transparent; color:#991B1B; border-color:rgba(153,27,27,.25); }
.db-btn-danger:hover  { background:#FEF2F2; }
.db-btn-warn    { background:transparent; color:#92400E; border-color:rgba(146,64,14,.25); }
.db-btn-warn:hover    { background:#FFFBEB; }

/* KPI grille */
.kpi-grid-2 { display:grid; gap:1.2rem; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); }
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
    border-radius:99px 0 0 99px;
}
.kpi-card.c-green::before  { background:linear-gradient(180deg,var(--green-2),var(--green-3)); }
.kpi-card.c-gold::before   { background:linear-gradient(180deg,var(--gold-l),var(--gold)); }
.kpi-card.c-red::before    { background:linear-gradient(180deg,#EF4444,#B91C1C); }
.kpi-card.c-blue::before   { background:linear-gradient(180deg,#3B82F6,#1D4ED8); }
.kpi-card.c-purple::before { background:linear-gradient(180deg,#8B5CF6,#6D28D9); }
.kpi-card.c-teal::before   { background:linear-gradient(180deg,#14B8A6,#0F766E); }
.kpi-card.c-amber::before  { background:linear-gradient(180deg,#F59E0B,#D97706); }
.kpi-card:hover { transform:translateY(-3px); box-shadow:0 14px 38px rgba(15,31,24,.12); }
.kpi-label  { font-size:.73rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:var(--muted); }
.kpi-value  { font-family:var(--f-serif); font-size:2.5rem; font-weight:700; line-height:1; color:var(--green); }
.kpi-value.gold   { color:var(--gold); }
.kpi-value.red    { color:#DC2626; }
.kpi-value.blue   { color:#1D4ED8; }
.kpi-value.purple { color:#6D28D9; }
.kpi-value.teal   { color:#0F766E; }
.kpi-desc   { font-size:.81rem; color:var(--muted); line-height:1.5; }

/* Sections */
.db-section { display:flex; flex-direction:column; gap:1.2rem; }
.db-section-head { display:flex; align-items:baseline; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.db-section-head h2 { font-family:var(--f-serif); font-size:1.4rem; font-weight:600; color:var(--ink); margin:0; }
.db-section-head p  { font-size:.83rem; color:var(--muted); margin:0; }

/* Panneaux */
.data-pane {
    background:var(--white); border-radius:var(--r-lg);
    border:1px solid rgba(30,58,47,.08);
    box-shadow:0 4px 20px rgba(15,31,24,.06);
    overflow:hidden;
}

/* Lignes vendeur */
.seller-row {
    display:flex; align-items:center; gap:1.2rem; flex-wrap:wrap;
    padding:1.1rem 1.6rem; border-bottom:1px solid rgba(30,58,47,.06);
    transition:background .15s;
}
.seller-row:last-child { border-bottom:none; }
.seller-row:hover { background:rgba(30,58,47,.02); }
.seller-avatar {
    width:2.4rem; height:2.4rem; border-radius:99px; flex-shrink:0;
    background:linear-gradient(135deg,var(--green-2),var(--gold));
    display:grid; place-items:center;
    font-family:var(--f-serif); font-weight:700; font-size:.95rem; color:#fff;
}
.seller-name  { font-weight:700; color:var(--ink); font-size:.92rem; }
.seller-email { font-size:.80rem; color:var(--muted); margin-top:.1rem; }
.seller-meta  { font-size:.78rem; color:var(--muted); }
.seller-info  { flex:1; min-width:140px; }
.seller-actions { display:flex; gap:.5rem; flex-wrap:wrap; margin-left:auto; }

.pill { display:inline-flex; padding:.25rem .8rem; border-radius:99px; font-size:.71rem; font-weight:700; }
.pill-pending  { background:#FEFCE8; color:#92400E; border:1px solid #FDE68A; }
.pill-approved { background:#F0FDF4; color:#166534; border:1px solid #BBF7D0; }
.pill-rejected { background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; }

/* État vide */
.empty-state {
    background:var(--white); border-radius:var(--r-lg);
    border:1px dashed rgba(30,58,47,.15);
    padding:2.5rem 2rem; text-align:center;
}
.empty-state p { color:var(--muted); margin:.4rem 0 0; }

/* Loader */
.db-loader {
    padding:2rem 1.6rem; text-align:center;
    color:var(--muted); font-size:.88rem; font-style:italic;
}
</style>
@endpush

@section('content')
<div class="content-pane">
<div style="padding:2.5rem 2.5rem 3rem;">
<div class="db-wrap">

    {{-- En-tête --}}
    <div class="db-header">
        <div class="db-header-info">
            <p class="db-role-tag">Administrateur</p>
            <h1>Tableau de bord</h1>
            <p>Surveillance des vendeurs, validation des comptes et performances globales.</p>
        </div>
    </div>

    {{-- KPIs ligne 1 --}}
    <div class="kpi-grid-2">
        <div class="kpi-card c-green">
            <span class="kpi-label">Utilisateurs</span>
            <span class="kpi-value">{{ number_format($totalUsers) }}</span>
            <span class="kpi-desc">{{ number_format($buyers) }} acheteurs · {{ number_format($sellers) }} vendeurs · {{ number_format($admins) }} admins</span>
        </div>
        <div class="kpi-card c-red">
            <span class="kpi-label">Vendeurs en attente</span>
            <span class="kpi-value red">{{ number_format($pendingSellers) }}</span>
            <span class="kpi-desc">Comptes à approuver pour accéder à la vente.</span>
        </div>
        <div class="kpi-card c-blue">
            <span class="kpi-label">Produits à vérifier</span>
            <span class="kpi-value blue">{{ number_format($pendingProducts) }}</span>
            <span class="kpi-desc">En attente de validation qualité avant publication.</span>
        </div>
        <div class="kpi-card c-teal">
            <span class="kpi-label">Commandes</span>
            <span class="kpi-value teal">{{ number_format($orders) }}</span>
            <span class="kpi-desc">Total des commandes enregistrées sur la plateforme.</span>
        </div>
        <div class="kpi-card c-gold">
            <span class="kpi-label">Commissions</span>
            <span class="kpi-value gold">{{ number_format($totalCommission, 0, ',', ' ') }}</span>
            <span class="kpi-desc">XOF — revenus générés par les commissions.</span>
        </div>
        <div class="kpi-card c-amber">
            <span class="kpi-label">Expéditions actives</span>
            <span class="kpi-value">{{ number_format($pendingShipments) }}</span>
            <span class="kpi-desc">Envois nécessitant un suivi ou mise à jour.</span>
        </div>
        <div class="kpi-card c-purple">
            <span class="kpi-label">Catalogue</span>
            <span class="kpi-value purple">{{ number_format($products) }}</span>
            <span class="kpi-desc">Produits actifs publiés par les vendeurs.</span>
        </div>
    </div>

    {{-- Vendeurs récents --}}
    <div class="db-section">
        <div class="db-section-head">
            <div>
                <h2>Vendeurs récents</h2>
                <p>Les 5 derniers comptes vendeurs créés sur la plateforme.</p>
            </div>
        </div>
        <div class="data-pane">
            @forelse($recentSellers as $seller)
                <div class="seller-row">
                    <div class="seller-avatar">{{ mb_strtoupper(mb_substr($seller->name, 0, 1)) }}</div>
                    <div class="seller-info">
                        <div class="seller-name">{{ $seller->name }}</div>
                        <div class="seller-email">{{ $seller->email }}</div>
                    </div>
                    @php
                        $pClass = match($seller->seller_status) {
                            'approved' => 'pill-approved',
                            'rejected' => 'pill-rejected',
                            default    => 'pill-pending',
                        };
                    @endphp
                    <span class="pill {{ $pClass }}">{{ $seller->seller_status_label }}</span>
                    <span class="seller-meta">{{ $seller->created_at->format('d/m/Y') }}</span>
                </div>
            @empty
                <div class="db-loader">Aucun vendeur récent.</div>
            @endforelse
        </div>
    </div>

    {{-- Gestion des vendeurs en attente --}}
    <div class="db-section">
        <div class="db-section-head">
            <div>
                <h2>Vendeurs en attente d'approbation</h2>
                <p>Approuvez, rejetez ou supprimez les comptes vendeurs en attente.</p>
            </div>
        </div>
        <div class="data-pane" id="pending-sellers-pane">
            <div class="db-loader">Chargement…</div>
        </div>
    </div>

    {{-- ── SECTION MANQUANTE : Produits en attente de validation ── --}}
    <div class="db-section">
        <div class="db-section-head">
            <div>
                <h2>Produits en attente de validation</h2>
                <p>Approuvez ou rejetez les produits soumis par les vendeurs avant leur publication en boutique.</p>
            </div>
        </div>
        <div class="data-pane" id="pending-products-pane">
            <div class="db-loader">Chargement…</div>
        </div>
    </div>

</div>
</div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const pane = document.getElementById('pending-sellers-pane');

    function renderRow(seller) {
        return `
        <div class="seller-row" id="seller-row-${seller.id}">
            <div class="seller-avatar">${seller.name.charAt(0).toUpperCase()}</div>
            <div class="seller-info">
                <div class="seller-name">${seller.name}</div>
                <div class="seller-email">${seller.email}</div>
                ${seller.phone ? `<div class="seller-meta">${seller.phone}${seller.location ? ' · ' + seller.location : ''}</div>` : ''}
            </div>
            <span class="seller-meta">${new Date(seller.created_at).toLocaleDateString('fr-FR')}</span>
            <div class="seller-actions">
                <button class="db-btn db-btn-green db-btn-sm" onclick="approveSeller(${seller.id})">Approuver</button>
                <button class="db-btn db-btn-warn   db-btn-sm" onclick="rejectSeller(${seller.id})">Rejeter</button>
                <button class="db-btn db-btn-danger  db-btn-sm" onclick="deleteSeller(${seller.id})">Supprimer</button>
            </div>
        </div>`;
    }

    function loadPendingSellers() {
        pane.innerHTML = '<div class="db-loader">Chargement…</div>';
        fetch('/admin/sellers/pending', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(sellers => {
            if (!sellers.length) {
                pane.innerHTML = '<div class="db-loader" style="color:var(--green);font-style:normal;font-weight:600;">Aucun vendeur en attente.</div>';
                return;
            }
            pane.innerHTML = sellers.map(renderRow).join('');
        })
        .catch(() => {
            pane.innerHTML = '<div class="db-loader" style="color:#DC2626">Erreur lors du chargement.</div>';
        });
    }

    function apiPatch(url) {
        return fetch(url, {
            method: 'PATCH',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
        });
    }

    window.approveSeller = function (id) {
        if (!confirm('Approuver ce vendeur ?')) return;
        apiPatch(`/admin/sellers/${id}/approve`).then(loadPendingSellers);
    };
    window.rejectSeller = function (id) {
        if (!confirm('Rejeter ce vendeur ?')) return;
        apiPatch(`/admin/sellers/${id}/reject`).then(loadPendingSellers);
    };
    window.deleteSeller = function (id) {
        if (!confirm('Supprimer définitivement ce vendeur ? Cette action est irréversible.')) return;
        fetch(`/admin/sellers/${id}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
        }).then(loadPendingSellers);
    };

    loadPendingSellers();

    // ── Produits en attente ──────────────────────────────────────────
    const ppane = document.getElementById('pending-products-pane');

    function renderProductRow(p) {
        const seller  = p.seller   ? p.seller.name   : 'Vendeur inconnu';
        const cat     = p.category ? p.category.name : '—';
        const price   = parseInt(p.price).toLocaleString('fr-FR') + ' ' + (p.currency || 'XOF');
        // CORRECTION : on stocke le SLUG (route key du modèle Product) pas l'id
        const routeKey = p.slug || p.id;
        const imgHtml = p.image
            ? `<img src="${p.image}" alt="${p.name}" style="width:3.2rem;height:3.2rem;border-radius:.5rem;object-fit:cover;flex-shrink:0;">`
            : `<div style="width:3.2rem;height:3.2rem;border-radius:.5rem;background:var(--sand);flex-shrink:0;display:grid;place-items:center;color:var(--muted);font-size:.7rem;">IMG</div>`;
        return `
        <div class="seller-row" id="prod-row-${p.id}">
            ${imgHtml}
            <div class="seller-info">
                <div class="seller-name">${p.name}</div>
                <div class="seller-email">${cat} &middot; ${price} &middot; Vendeur : ${seller}</div>
            </div>
            <span class="pill pill-pending" id="prod-status-${p.id}">En attente</span>
            <div class="seller-actions">
                <button class="db-btn db-btn-green  db-btn-sm" onclick="approveProduct('${routeKey}', ${p.id})">Approuver</button>
                <button class="db-btn db-btn-danger db-btn-sm" onclick="rejectProduct('${routeKey}',  ${p.id})">Rejeter</button>
            </div>
        </div>`;
    }

    function loadPendingProducts() {
        ppane.innerHTML = '<div class="db-loader">Chargement…</div>';
        fetch('/admin/products/pending', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(products => {
            if (!products.length) {
                ppane.innerHTML = '<div class="db-loader" style="color:var(--green);font-style:normal;font-weight:600;">À jour — aucun produit en attente de validation.</div>';
                return;
            }
            ppane.innerHTML = products.map(renderProductRow).join('');
        })
        .catch(err => {
            ppane.innerHTML = `<div class="db-loader" style="color:#DC2626">Erreur : ${err.message}. Vérifiez que vous êtes bien connecté en tant qu'admin.</div>`;
        });
    }

    function patchProduct(routeKey, rowId, action) {
        const row  = document.getElementById('prod-row-' + rowId);
        const btns = row ? row.querySelectorAll('button') : [];
        btns.forEach(b => { b.disabled = true; b.style.opacity = '0.5'; });

        // Indiquer visuellement que ça charge
        const statusPill = row ? row.querySelector('.pill') : null;
        if (statusPill) { statusPill.textContent = '…'; statusPill.style.opacity = '0.5'; }

        fetch(`/admin/products/${routeKey}/${action}`, {
            method: 'PATCH',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        })
        .then(r => {
            if (!r.ok) {
                // Essayer de lire le message d'erreur JSON, sinon afficher le status HTTP
                return r.text().then(txt => {
                    let msg = 'HTTP ' + r.status;
                    try { msg = JSON.parse(txt).message || msg; } catch(e) {}
                    throw new Error(msg);
                });
            }
            return r.json();
        })
        .then(product => {
            // Vérifier que le serveur a VRAIMENT changé le statut
            const expectedStatus = action === 'approve' ? 'approved' : 'rejected';
            if (product.quality_status !== expectedStatus) {
                throw new Error('Le serveur a répondu mais le statut n\'a pas été mis à jour (reçu : ' + product.quality_status + ')');
            }

            // Succès confirmé — retirer la ligne avec animation
            if (row) {
                row.style.transition = 'opacity .4s, transform .4s';
                row.style.opacity    = '0';
                row.style.transform  = 'translateX(24px)';
                setTimeout(() => {
                    row.remove();
                    if (!ppane.querySelector('.seller-row')) {
                        ppane.innerHTML = '<div class="db-loader" style="color:var(--green);font-style:normal;font-weight:600;">À jour — aucun produit en attente.</div>';
                    }
                }, 420);
            }

            // Confirmation visuelle en haut de page
            showToast(
                action === 'approve'
                    ? '✓ Produit approuvé — il est maintenant visible en boutique.'
                    : '✓ Produit rejeté.',
                action === 'approve' ? 'success' : 'warn'
            );
        })
        .catch(err => {
            // Rétablir les boutons
            btns.forEach(b => { b.disabled = false; b.style.opacity = '1'; });
            if (statusPill) { statusPill.textContent = 'En attente'; statusPill.style.opacity = '1'; }
            showToast('Erreur : ' + err.message, 'error');
        });
    }

    function showToast(message, type) {
        const colors = {
            success: { bg: '#F0FDF4', border: '#BBF7D0', color: '#166534' },
            warn:    { bg: '#FFFBEB', border: '#FDE68A', color: '#92400E' },
            error:   { bg: '#FEF2F2', border: '#FECACA', color: '#991B1B' },
        };
        const c = colors[type] || colors.success;
        const toast = document.createElement('div');
        toast.style.cssText = `
            position:fixed; bottom:2rem; right:2rem; z-index:9999;
            background:${c.bg}; border:1px solid ${c.border}; color:${c.color};
            padding:.9rem 1.4rem; border-radius:.75rem;
            font-family:var(--f-sans,sans-serif); font-size:.9rem; font-weight:600;
            box-shadow:0 8px 30px rgba(0,0,0,.12);
            opacity:0; transform:translateY(10px);
            transition:opacity .3s, transform .3s;
            max-width:360px; line-height:1.5;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.opacity   = '1';
            toast.style.transform = 'translateY(0)';
        });
        setTimeout(() => {
            toast.style.opacity   = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 350);
        }, 5000);
    }

    window.approveProduct = function (routeKey, rowId) {
        if (!confirm('Approuver ce produit et le publier en boutique ?')) return;
        patchProduct(routeKey, rowId, 'approve');
    };
    window.rejectProduct = function (routeKey, rowId) {
        if (!confirm('Rejeter ce produit ? Le vendeur devra le resoumettre.')) return;
        patchProduct(routeKey, rowId, 'reject');
    };

    loadPendingProducts();
});
</script>!