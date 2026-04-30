@extends('layouts.app')
@section('title', 'Paiement — PME Bénin')

@php
$imgFallback = 'https://images.pexels.com/photos/1181650/pexels-photo-1181650.jpeg?auto=compress&cs=tinysrgb&w=400';
function coImg($img, $fb) {
    if (!$img) return $fb;
    if (str_starts_with($img, 'http')) return $img;
    return asset('storage/' . ltrim($img, '/'));
}
$commissionRate = 8;
@endphp

@push('styles')
<style>
.co-wrap { display:flex; flex-direction:column; gap:1.8rem; }
.co-grid { display:grid; gap:1.8rem; grid-template-columns:1fr 360px; align-items:start; }
@media(max-width:860px){ .co-grid { grid-template-columns:1fr; } }

.co-head {
    background:linear-gradient(135deg,var(--green) 0%,var(--green-2) 100%);
    border-radius:var(--r-xl); padding:2.2rem 3rem; position:relative; overflow:hidden;
}
.co-head::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
}
.co-kicker { font-size:.71rem; font-weight:700; text-transform:uppercase; letter-spacing:.18em; color:#F0C040; margin-bottom:.5rem; display:flex; align-items:center; gap:.5rem; }
.co-kicker::before { content:''; width:1.2rem; height:1px; background:#F0C040; display:block; }
.co-head h1 { font-family:var(--f-serif); font-size:clamp(1.6rem,2.8vw,2.2rem); font-weight:600; color:#fff; margin:0 0 .3rem; }
.co-head p { color:rgba(255,255,255,.70); font-size:.93rem; }

.co-pane { background:#fff; border-radius:var(--r-lg); border:1px solid rgba(30,58,47,.08); box-shadow:0 4px 20px rgba(15,31,24,.06); overflow:hidden; }
.co-pane-head { padding:1.2rem 1.8rem; border-bottom:1px solid rgba(30,58,47,.07); }
.co-pane-head h2 { font-family:var(--f-serif); font-size:1.15rem; font-weight:600; color:var(--ink); margin:0; }
.co-pane-body { padding:1.6rem 1.8rem; display:flex; flex-direction:column; gap:1.2rem; }

.co-field { display:flex; flex-direction:column; gap:.4rem; }
.co-field label { font-size:.84rem; font-weight:600; color:var(--ink); }
.co-field textarea, .co-field select {
    background:var(--sand,#F5EDD8); border:1.5px solid transparent; border-radius:.65rem;
    padding:.85rem 1rem; font-size:.94rem; font-family:var(--f-sans); color:var(--ink);
    transition:border-color .2s, box-shadow .2s; width:100%;
}
.co-field textarea:focus, .co-field select:focus {
    outline:none; background:#fff; border-color:var(--green-2);
    box-shadow:0 0 0 3px rgba(45,82,66,.10);
}
.co-field textarea { resize:vertical; min-height:90px; }

.recap-item { display:flex; align-items:center; gap:.9rem; }
.recap-thumb { width:3rem; height:3rem; border-radius:.5rem; flex-shrink:0; background-size:cover; background-position:center; background-color:#F5EDD8; }
.recap-name  { font-weight:700; font-size:.9rem; color:var(--ink); }
.recap-sub   { font-size:.78rem; color:var(--muted); margin-top:.1rem; }
.recap-price { font-family:var(--f-serif); font-size:.98rem; font-weight:700; color:var(--green); margin-left:auto; flex-shrink:0; }

hr.divider { border:none; border-top:1px solid rgba(30,58,47,.07); margin:.4rem 0; }
.total-row { display:flex; justify-content:space-between; align-items:center; }
.total-row span   { font-size:.88rem; font-weight:600; color:var(--muted); }
.total-row strong { font-family:var(--f-serif); font-size:1.5rem; font-weight:700; color:var(--green); }

.pay-btn {
    width:100%; background:linear-gradient(135deg,var(--green-2),var(--green)); color:#fff;
    border:none; border-radius:.75rem; padding:1.05rem; font-size:1rem; font-weight:700;
    font-family:var(--f-sans); cursor:pointer; box-shadow:0 8px 24px rgba(30,58,47,.22);
    transition:transform .2s, box-shadow .2s;
    display:flex; align-items:center; justify-content:center; gap:.5rem;
}
.pay-btn:hover { transform:translateY(-2px); box-shadow:0 14px 36px rgba(30,58,47,.30); }

.vendor-block { border:1px solid rgba(30,58,47,.10); border-radius:var(--r-md); overflow:hidden; }
.vendor-head  { padding:.75rem 1.2rem; background:rgba(30,58,47,.04); display:flex; align-items:center; justify-content:space-between; font-size:.85rem; font-weight:700; color:var(--ink); }
.vendor-items { padding:1rem 1.2rem; display:flex; flex-direction:column; gap:.8rem; }
.vendor-pay-btn {
    width:100%; margin:.6rem 0 0; background:rgba(30,58,47,.06); color:var(--green-2);
    border:1.5px solid rgba(30,58,47,.15); border-radius:.65rem; padding:.75rem;
    font-size:.87rem; font-weight:700; font-family:var(--f-sans); cursor:pointer;
    transition:background .2s;
}
.vendor-pay-btn:hover { background:rgba(30,58,47,.12); }

.commission-note { background:rgba(184,134,11,.08); border:1px solid rgba(184,134,11,.20); border-radius:.65rem; padding:.7rem 1rem; font-size:.8rem; color:#92400E; font-weight:600; }

.sandbox-box { background:linear-gradient(135deg,#FFFBEB,#FEF3C7); border:1px solid #FDE68A; border-radius:var(--r-md); padding:1.2rem 1.4rem; }
.sandbox-box h3 { font-size:.88rem; font-weight:800; color:#92400E; margin:0 0 .7rem; }
.sandbox-box table { width:100%; border-collapse:collapse; font-size:.82rem; }
.sandbox-box td { padding:.28rem .4rem; color:#78350F; }
.sandbox-box td:first-child { font-weight:700; white-space:nowrap; width:38%; }
.sandbox-box td code { font-family:monospace; background:rgba(0,0,0,.07); padding:.1rem .3rem; border-radius:.25rem; }
.sandbox-box tr+tr td { border-top:1px solid rgba(0,0,0,.05); }

.secure-note { display:flex; align-items:center; gap:.4rem; font-size:.77rem; color:var(--muted); justify-content:center; margin-top:.5rem; }
.flash-err { background:#FEF2F2; border:1px solid #FECACA; border-radius:var(--r-md); padding:.9rem 1.2rem; color:#991B1B; font-size:.9rem; font-weight:600; }
</style>
@endpush

@section('content')
<div class="content-pane">
<div style="padding:2.5rem 2.5rem 3rem;">
<div class="co-wrap">

    <div class="co-head">
        <p class="co-kicker">Paiement sécurisé</p>
        <h1>Finalisez votre commande</h1>
        <p>Renseignez votre adresse et choisissez comment payer.</p>
    </div>

    @if(session('error')) <div class="flash-err">{{ session('error') }}</div> @endif

    <div class="co-grid">

        {{-- Colonne gauche --}}
        <div style="display:flex;flex-direction:column;gap:1.4rem;">

            {{-- Formulaire principal (pour "tout payer") --}}
            <div class="co-pane">
                <div class="co-pane-head"><h2>Informations de livraison</h2></div>
                <form method="POST" action="{{ route('checkout.place') }}" id="mainForm">
                    @csrf
                    <div class="co-pane-body">
                        <div class="co-field">
                            <label for="shipping_address">Adresse de livraison</label>
                            <textarea id="shipping_address" name="shipping_address" required
                                placeholder="Rue, quartier, ville, pays…">{{ old('shipping_address') }}</textarea>
                        </div>
                        <div class="co-field">
                            <label for="payment_method">Moyen de paiement</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="mobile_money">Mobile Money (MTN, Moov…)</option>
                                <option value="card">Carte bancaire</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Paiement par vendeur (uniquement si multi-vendeurs) --}}
            @if($isMulti)
            <div class="co-pane">
                <div class="co-pane-head"><h2>Payer par vendeur individuellement</h2></div>
                <div class="co-pane-body">
                    <p style="font-size:.87rem;color:var(--muted);margin:0">
                        Votre panier contient des articles de <strong>{{ $byVendor->count() }} vendeurs</strong>.
                        Vous pouvez payer chacun séparément, ou tout payer en une seule fois à droite.
                    </p>
                    @foreach($byVendor as $sellerId => $sellerItems)
                        @php
                            $vendorTotal = collect($sellerItems)->sum(fn($i) => $i['price'] * $i['quantity']);
                            $vendorName  = $sellerItems->first()['seller_name'];
                        @endphp
                        <div class="vendor-block">
                            <div class="vendor-head">
                                <span>{{ $vendorName }}</span>
                                <span style="font-family:var(--f-serif)">{{ number_format($vendorTotal,0,',', ' ') }} XOF</span>
                            </div>
                            <div class="vendor-items">
                                @foreach($sellerItems as $item)
                                    <div class="recap-item">
                                        <div class="recap-thumb" style="background-image:url('{{ coImg($item['image']??null,$imgFallback) }}')"></div>
                                        <div>
                                            <div class="recap-name">{{ $item['name'] }}</div>
                                            <div class="recap-sub">{{ $item['quantity'] }} × {{ number_format($item['price'],0,',', ' ') }} XOF</div>
                                        </div>
                                        <span class="recap-price">{{ number_format($item['price']*$item['quantity'],0,',',' ') }}</span>
                                    </div>
                                @endforeach
                                {{-- Formulaire caché pour ce vendeur --}}
                                <form method="POST" action="{{ route('checkout.place') }}" class="vendor-form" data-seller="{{ $sellerId }}">
                                    @csrf
                                    <input type="hidden" name="seller_id"        value="{{ $sellerId }}">
                                    <input type="hidden" name="payment_method"   value="">
                                    <input type="hidden" name="shipping_address" value="">
                                    <button type="button" class="vendor-pay-btn"
                                            onclick="submitVendorForm({{ $sellerId }}, this.closest('form'))">
                                        Payer {{ $vendorName }} — {{ number_format($vendorTotal,0,',', ' ') }} XOF
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Sandbox --}}
            <div class="sandbox-box">
                <h3>Mode test — Flutterwave Sandbox</h3>
                <p style="font-size:.79rem;color:#78350F;margin:0 0 .6rem">Aucun vrai débit ne sera effectué.</p>
                <table>
                    <tr><td>Numéro</td> <td><code>4187 4274 1556 4246</code></td></tr>
                    <tr><td>Expiry</td> <td><code>09/32</code></td></tr>
                    <tr><td>CVV</td>    <td><code>828</code></td></tr>
                    <tr><td>PIN</td>    <td><code>3310</code></td></tr>
                    <tr><td>OTP</td>    <td><code>12345</code></td></tr>
                </table>
            </div>
        </div>

        {{-- Colonne droite --}}
        <div style="display:flex;flex-direction:column;gap:1.2rem;">
            <div class="co-pane">
                <div class="co-pane-head"><h2>Récapitulatif</h2></div>
                <div class="co-pane-body">
                    @foreach($cart as $item)
                        <div class="recap-item">
                            <div class="recap-thumb" style="background-image:url('{{ coImg($item['image']??null,$imgFallback) }}')"></div>
                            <div>
                                <div class="recap-name">{{ $item['name'] }}</div>
                                <div class="recap-sub">{{ $item['quantity'] }} × {{ number_format($item['price'],0,',', ' ') }} {{ $item['currency'] }}</div>
                            </div>
                            <span class="recap-price">{{ number_format($item['price']*$item['quantity'],0,',',' ') }}</span>
                        </div>
                    @endforeach

                    <hr class="divider">

                    <div class="total-row">
                        <span>Total à payer</span>
                        <strong>{{ number_format($total,0,',',' ') }} XOF</strong>
                    </div>

                  

                    <button type="submit" form="mainForm" class="pay-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        {{ $isMulti ? 'Tout payer — ' : 'Payer — ' }}{{ number_format($total,0,',',' ') }} XOF
                    </button>

                    <p class="secure-note">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Paiement sécurisé par Flutterwave
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
function submitVendorForm(sellerId, form) {
    const mainForm = document.getElementById('mainForm');
    const address  = mainForm.querySelector('[name="shipping_address"]').value.trim();
    const method   = mainForm.querySelector('[name="payment_method"]').value;
    if (!address) {
        alert('Veuillez remplir l\'adresse de livraison avant de payer.');
        mainForm.querySelector('[name="shipping_address"]').focus();
        return;
    }
    form.querySelector('[name="shipping_address"]').value = address;
    form.querySelector('[name="payment_method"]').value   = method;
    form.submit();
}
</script>
@endpush