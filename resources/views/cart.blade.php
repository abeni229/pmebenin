@extends('layouts.app')
@section('title', 'Panier — PME Bénin')

@php
$imgFallback = 'https://images.pexels.com/photos/1181650/pexels-photo-1181650.jpeg?auto=compress&cs=tinysrgb&w=400';
function cartImg($img, $fb) {
    if (!$img) return $fb;
    if (str_starts_with($img, 'http')) return $img;
    return asset('storage/' . ltrim($img, '/'));
}
@endphp

@push('styles')
<style>
.cart-wrap { display:flex; flex-direction:column; gap:1.8rem; }
.cart-pane {
    background:#fff; border-radius:var(--r-xl);
    border:1px solid rgba(30,58,47,.08);
    box-shadow:0 4px 24px rgba(15,31,24,.07);
    overflow:hidden;
}
.cart-head {
    padding:1.4rem 2rem; border-bottom:1px solid rgba(30,58,47,.07);
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.8rem;
}
.cart-head h2 { font-family:var(--f-serif); font-size:1.3rem; font-weight:600; color:var(--ink); margin:0; }
.cart-head p  { font-size:.85rem; color:var(--muted); margin:0; }
.cart-table { width:100%; border-collapse:collapse; }
.cart-table th {
    padding:.9rem 1.2rem; text-align:left;
    font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em;
    color:var(--muted); border-bottom:1px solid rgba(30,58,47,.07); white-space:nowrap;
}
.cart-table td {
    padding:1rem 1.2rem; border-bottom:1px solid rgba(30,58,47,.05);
    font-size:.92rem; color:var(--ink); vertical-align:middle;
}
.cart-table tr:last-child td { border-bottom:none; }
.cart-table tr:hover td { background:rgba(30,58,47,.015); }

.prod-cell { display:flex; align-items:center; gap:.9rem; }
.prod-thumb {
    width:3.4rem; height:3.4rem; border-radius:.55rem; flex-shrink:0;
    background-size:cover; background-position:center; background-color:var(--sand);
    position:relative; overflow:hidden;
}
.prod-name { font-weight:700; font-size:.92rem; color:var(--ink); }
.prod-vendor{ font-size:.78rem; color:var(--muted); margin-top:.1rem; }
.prod-stock-warn { font-size:.74rem; color:#C05621; font-weight:700; margin-top:.2rem; }

.qty-input {
    width:5rem; padding:.5rem .7rem;
    background:var(--sand); border:1.5px solid transparent; border-radius:.55rem;
    font-size:.9rem; font-family:var(--f-sans); color:var(--ink); text-align:center;
    transition:border-color .2s, box-shadow .2s;
}
.qty-input:focus { outline:none; border-color:var(--green-2); box-shadow:0 0 0 3px rgba(45,82,66,.10); }
.qty-input.over-limit { border-color:#C05621; box-shadow:0 0 0 3px rgba(192,86,33,.10); }

.subtotal-cell { font-weight:700; font-family:var(--f-serif); font-size:1rem; color:var(--green); }

/* Bouton supprimer — FONCTIONNEL via form POST */
.btn-remove {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.45rem .95rem; border-radius:99px;
    font-size:.8rem; font-weight:600; font-family:var(--f-sans);
    background:transparent; color:#991B1B;
    border:1.5px solid rgba(153,27,27,.22);
    cursor:pointer; transition:background .18s, border-color .18s;
}
.btn-remove:hover { background:#FEF2F2; border-color:rgba(153,27,27,.40); }

.cart-actions {
    padding:1.2rem 2rem; border-top:1px solid rgba(30,58,47,.07);
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.8rem;
}
.cart-total-row {
    padding:1.2rem 2rem; background:rgba(30,58,47,.03);
    display:flex; justify-content:space-between; align-items:center;
    font-weight:700; border-top:1px solid rgba(30,58,47,.07);
}
.cart-total-row span { font-family:var(--f-serif); font-size:1.5rem; color:var(--green); }

.db-btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.65rem 1.35rem; border-radius:99px;
    font-size:.87rem; font-weight:600; font-family:var(--f-sans);
    border:1.5px solid transparent; cursor:pointer;
    transition:transform .2s, box-shadow .2s; text-decoration:none;
}
.db-btn:hover { transform:translateY(-1px); }
.db-btn-outline { background:transparent; color:var(--green-2); border-color:rgba(30,58,47,.22); }
.db-btn-outline:hover { background:var(--sand); }
.db-btn-gold { background:linear-gradient(135deg,var(--gold-xl,#F0C040),var(--gold-l,#D4A017)); color:var(--green); box-shadow:0 4px 14px rgba(184,134,11,.25); }

.empty-cart {
    background:#fff; border-radius:var(--r-xl); border:1px dashed rgba(30,58,47,.15);
    padding:3.5rem 2rem; text-align:center;
}
.empty-cart p { color:var(--muted); margin:.5rem 0 1.4rem; }

.flash-ok  { background:#F0FDF4; border:1px solid #BBF7D0; border-radius:var(--r-md); padding:.9rem 1.2rem; color:#166534; font-size:.9rem; font-weight:600; margin-bottom:1rem; }
.flash-err { background:#FEF2F2; border:1px solid #FECACA; border-radius:var(--r-md); padding:.9rem 1.2rem; color:#991B1B; font-size:.9rem; font-weight:600; margin-bottom:1rem; }
</style>
@endpush

@section('content')
<div class="content-pane">
<div style="padding:2.5rem 2.5rem 3rem;">
<div class="cart-wrap">

    {{-- Flash --}}
    @if(session('status')) <div class="flash-ok">{{ session('status') }}</div> @endif
    @if(session('error'))  <div class="flash-err">{{ session('error') }}</div>  @endif

    @if(empty($cart))
        <div class="empty-cart">
            <div style="font-family:var(--f-serif);font-size:3rem;color:var(--muted);opacity:.3;margin-bottom:.5rem">◌</div>
            <p>Votre panier est vide. Découvrez nos produits locaux.</p>
            <a href="{{ route('shop') }}" class="db-btn db-btn-gold" style="display:inline-flex">Parcourir la boutique</a>
        </div>
    @else

        {{-- Formulaire mise à jour --}}
        <form method="POST" action="{{ route('cart.update') }}" id="cartForm">
            @csrf
            <div class="cart-pane">
                <div class="cart-head">
                    <div>
                        <h2>Votre panier</h2>
                        <p>{{ count($cart) }} article{{ count($cart) > 1 ? 's' : '' }}</p>
                    </div>
                </div>

                <table class="cart-table">
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
                            @php
                                $img       = cartImg($item['image'] ?? null, $imgFallback);
                                $realStock = \App\Models\Product::find($item['id'])?->stock ?? 99;
                            @endphp
                            <tr>
                                <td>
                                    <div class="prod-cell">
                                        <div class="prod-thumb" style="background-image:url('{{ $img }}')">
                                            <img src="{{ $img }}" alt="" style="opacity:0;position:absolute;width:1px;height:1px;"
                                                 onerror="this.closest('.prod-thumb').style.backgroundImage='url({{ $imgFallback }})'">
                                        </div>
                                        <div>
                                            <div class="prod-name">{{ $item['name'] }}</div>
                                            <div class="prod-vendor">{{ $item['seller_name'] }}</div>
                                            @if($realStock <= 5)
                                                <div class="prod-stock-warn">Plus que {{ $realStock }} en stock</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item['category'] }}</td>
                                <td>
                                    <input
                                        type="number"
                                        name="items[{{ $item['id'] }}][quantity]"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        max="{{ $realStock }}"
                                        class="qty-input"
                                        data-price="{{ $item['price'] }}"
                                        data-id="{{ $item['id'] }}"
                                        data-stock="{{ $realStock }}"
                                    >
                                </td>
                                <td>{{ number_format($item['price'], 0, ',', ' ') }} {{ $item['currency'] }}</td>
                                <td class="subtotal-cell" id="sub-{{ $item['id'] }}">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} {{ $item['currency'] }}
                                </td>
                                <td>
                                    {{-- BOUTON SUPPRIMER FONCTIONNEL — form indépendant avec DELETE --}}
                                    <form method="POST" action="{{ route('cart.remove', ['product' => $item['id']]) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-remove" title="Supprimer">
                                            ✕ Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="cart-actions">
                    <button type="submit" class="db-btn db-btn-outline">Mettre à jour le panier</button>
                    <button type="button" id="checkoutBtn" class="db-btn db-btn-gold">Passer à la caisse</button>
                </div>

                <div class="cart-total-row">
                    <strong>Total du panier</strong>
                    <span id="grand-total">{{ number_format($total, 0, ',', ' ') }} XOF</span>
                </div>
            </div>
        </form>

    @endif
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs  = document.querySelectorAll('.qty-input');
    const totalEl = document.getElementById('grand-total');

    function recalcTotal() {
        let sum = 0;
        inputs.forEach(inp => sum += (parseFloat(inp.dataset.price)||0) * (parseInt(inp.value)||1));
        if (totalEl) totalEl.textContent = sum.toLocaleString('fr-FR') + ' XOF';
    }

    inputs.forEach(inp => {
        function enforce() {
            const stock = parseInt(inp.dataset.stock) || 1;
            let qty = parseInt(inp.value) || 1;
            if (qty < 1) qty = 1;
            if (qty > stock) {
                qty = stock;
                inp.classList.add('over-limit');
                setTimeout(() => inp.classList.remove('over-limit'), 1500);
            }
            inp.value = qty;

            const sub = document.getElementById('sub-' + inp.dataset.id);
            if (sub) {
                const price = parseFloat(inp.dataset.price) || 0;
                sub.textContent = (price * qty).toLocaleString('fr-FR') + ' XOF';
            }
            recalcTotal();
        }
        inp.addEventListener('input', enforce);
        inp.addEventListener('change', enforce);
        inp.addEventListener('blur', enforce);
    });

    // Checkout : sauvegarder puis rediriger
    const form        = document.getElementById('cartForm');
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn && form) {
        checkoutBtn.addEventListener('click', function () {
            let flag = form.querySelector('[name="redirect_to_checkout"]');
            if (!flag) {
                flag = document.createElement('input');
                flag.type = 'hidden';
                flag.name = 'redirect_to_checkout';
                form.appendChild(flag);
            }
            flag.value = '1';
            form.submit();
        });
    }
});
</script>
@endpush