@props(['product'])

<article class="ds-product-card ds-shop-card" data-reveal>
    <a href="{{ route('product.show', $product) }}" class="ds-product-link">
        <div class="ds-product-image" style="background-image: url('{{ $product->image ?: 'https://images.pexels.com/photos/1181650/pexels-photo-1181650.jpeg?auto=compress&cs=tinysrgb&h=900&w=1200' }}');"></div>
        <span class="ds-product-badge">{{ $product->category->name ?? 'Produit' }}</span>
        <div class="ds-product-copy">
            <h3>{{ $product->name }}</h3>
            <p>{{ \Illuminate\Support\Str::limit($product->description ?? 'Aucune description disponible pour ce produit.', 110) }}</p>
        </div>
    </a>
    <div class="ds-shop-footer">
        <span class="ds-product-price">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->currency }}</span>
        <span class="ds-product-meta">{{ $product->seller->name ?? 'Vendeur local' }}</span>
    </div>
</article>
