@props(['product'])

@php
    /**
     * CORRECTION IMAGE :
     * Le contrôleur stocke parfois une URL absolue (Storage::disk('public')->url(...))
     * et parfois un chemin relatif ('products/xxx.jpg').
     * On normalise ici : si c'est déjà une URL complète on l'utilise,
     * sinon on le passe par asset('storage/...').
     */
    $imageUrl = null;
    if ($product->image) {
        if (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://')) {
            $imageUrl = $product->image;
        } else {
            $imageUrl = asset('storage/' . ltrim($product->image, '/'));
        }
    }
    $fallback = 'https://images.pexels.com/photos/1181650/pexels-photo-1181650.jpeg?auto=compress&cs=tinysrgb&h=900&w=1200';
@endphp

<article class="ds-product-card" data-reveal>
    <a href="{{ route('product.show', $product) }}" class="ds-product-link" style="display: contents;">
        <img
            src="{{ $imageUrl ?: $fallback }}"
            alt="{{ $product->name }}"
            class="ds-product-image"
            loading="lazy"
            onerror="this.onerror=null; this.src='{{ $fallback }}';"
        >
        <span class="ds-product-badge">{{ $product->category->name ?? 'Produit' }}</span>
        <div class="ds-product-copy">
            <h3>{{ $product->name }}</h3>
            <p>{{ \Illuminate\Support\Str::limit($product->description ?? 'Aucune description disponible pour ce produit.', 100) }}</p>
        </div>
    </a>
    <div class="ds-product-footer">
        <span class="ds-product-price">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->currency }}</span>
        <span class="ds-product-meta">{{ $product->seller->name ?? 'Vendeur local' }}</span>
    </div>
</article>