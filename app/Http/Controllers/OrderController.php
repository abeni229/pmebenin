<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'seller', 'payment', 'shipment'])
            ->where('buyer_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        if (request()->wantsJson()) {
            return $orders;
        }

        return view('orders', ['orders' => $orders]);
    }

    public function cart()
    {
        $cart = session()->get('cart', []);

        return view('cart', [
            'cart' => $cart,
            'total' => collect($cart)->reduce(fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0),
        ]);
    }

    public function addToCart(Request $request, Product $product)
    {
        if (! $product->is_active || $product->stock <= 0) {
            return back()->with('status', 'Ce produit est en rupture de stock ou n’est plus disponible.');
        }

        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);

        $current = $cart[$product->id] ?? [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'currency' => $product->currency,
            'image' => $product->image,
            'category' => $product->category->name ?? 'Produit',
            'seller_id' => $product->seller_id,
            'seller_name' => $product->seller->name ?? 'Vendeur local',
            'quantity' => 0,
        ];

        $current['quantity'] = min($product->stock, $current['quantity'] + $quantity);
        $cart[$product->id] = $current;

        session()->put('cart', $cart);

        return back()->with('status', 'Produit ajouté au panier.');
    }

    public function removeFromCart(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return back()->with('status', 'Produit retiré du panier.');
    }

    public function updateCart(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session()->get('cart', []);

        foreach ($data['items'] as $productId => $item) {
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = max(1, (int) $item['quantity']);
            }
        }

        session()->put('cart', $cart);

        return back()->with('status', 'Panier mis à jour.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('status', 'Votre panier est vide.');
        }

        return view('checkout', [
            'cart' => $cart,
            'total' => collect($cart)->reduce(fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0),
            'paymentMethods' => Payment::allowedMethods(),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'buyer') {
            return redirect()->back()->with('status', 'Seuls les acheteurs peuvent finaliser une commande.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('status', 'Votre panier est vide.');
        }

        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:1024'],
            'payment_method' => ['required', 'string', Rule::in(Payment::allowedMethods())],
        ]);

        $items = collect($cart);
        $productIds = $items->pluck('id')->toArray();
        $products = Product::whereIn('id', $productIds)
            ->where('is_active', true)
            ->with('seller')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($productIds)) {
            return redirect()->route('cart')->with('status', 'Un ou plusieurs produits du panier ne sont plus disponibles.');
        }

        foreach ($items as $item) {
            $product = $products[$item['id']];

            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart')->with('status', "Stock insuffisant pour le produit {$product->name}." );
            }
        }

        $sellerIds = $products->pluck('seller_id')->unique();

        if ($sellerIds->count() > 1) {
            return redirect()->route('cart')->with('status', 'Les produits doivent appartenir au même vendeur pour cette commande.');
        }

        $sellerId = $sellerIds->first();
        $total = $items->reduce(fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);

        $order = DB::transaction(function () use ($user, $data, $items, $products, $sellerId, $total) {
            $order = Order::create([
                'buyer_id' => $user->id,
                'seller_id' => $sellerId,
                'total_amount' => $total,
                'currency' => 'XOF',
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'shipping_address' => $data['shipping_address'],
                'shipping_status' => 'pending',
            ]);

            foreach ($items as $item) {
                $product = $products[$item['id']];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'method' => $data['payment_method'],
                'provider' => $this->resolvePaymentProvider($data['payment_method']),
                'status' => 'pending',
            ]);

            Shipment::create([
                'order_id' => $order->id,
                'status' => 'preparing',
            ]);

            return $order;
        });

        session()->forget('cart');

        if ($request->wantsJson()) {
            return response()->json($order->load(['items.product', 'payment', 'shipment']), 201);
        }

        return redirect('/orders')->with('status', 'Commande créée. Suivez sa livraison depuis votre espace commande.');
    }

    public function sellerOrders()
    {
        return Order::with(['items.product', 'buyer', 'payment', 'shipment'])
            ->where('seller_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($user->role !== 'seller' || $order->seller_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])],
        ]);

        $order->status = $data['status'];
        $order->save();

        return redirect()->back()->with('status', 'Statut de commande mis à jour.');
    }

    public function updateShipping(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($user->role !== 'seller' || $order->seller_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $data = $request->validate([
            'shipping_status' => ['required', Rule::in(['pending', 'preparing', 'shipped', 'delivered', 'cancelled'])],
        ]);

        $order->shipping_status = $data['shipping_status'];
        $order->save();

        return redirect()->back()->with('status', 'État de livraison mis à jour.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'buyer') {
            return response()->json(['message' => 'Seulement les acheteurs peuvent passer une commande.'], 403);
        }

        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:1024'],
            'payment_method' => ['required', 'string', Rule::in(Payment::allowedMethods())],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $items = collect($data['items']);
        $quantitiesByProduct = $items->groupBy('product_id')->map(fn ($group) => $group->sum('quantity'));

        $products = Product::whereIn('id', $quantitiesByProduct->keys())
            ->where('is_active', true)
            ->with('seller')
            ->get()
            ->keyBy('id');

        if ($products->count() !== $quantitiesByProduct->count()) {
            return response()->json(['message' => 'Un ou plusieurs produits sont introuvables ou inactifs.'], 422);
        }

        $sellerId = $products->first()->seller_id;

        foreach ($products as $product) {
            if ($product->seller?->seller_status !== 'approved') {
                return response()->json(['message' => "Le vendeur du produit {$product->name} n'est pas approuvé."], 422);
            }
        }

        if ($products->pluck('seller_id')->unique()->count() > 1) {
            return response()->json(['message' => 'Les produits doivent appartenir au même vendeur dans une commande.'], 422);
        }

        foreach ($quantitiesByProduct as $productId => $quantity) {
            $product = $products[$productId];

            if ($quantity > $product->stock) {
                return response()->json([
                    'message' => "Stock insuffisant pour le produit {$product->name}.",
                ], 422);
            }
        }

        $total = $quantitiesByProduct->reduce(function ($carry, $quantity, $productId) use ($products) {
            return $carry + $products[$productId]->price * $quantity;
        }, 0);

        try {
            $order = DB::transaction(function () use ($data, $quantitiesByProduct, $sellerId, $total) {
                $products = Product::whereIn('id', $quantitiesByProduct->keys())
                    ->where('is_active', true)
                    ->with('seller')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($quantitiesByProduct as $productId => $quantity) {
                    $product = $products[$productId];

                    if ($product->seller?->seller_status !== 'approved') {
                        throw new \RuntimeException("Le vendeur du produit {$product->name} n'est pas approuvé.");
                    }

                    if ($quantity > $product->stock) {
                        throw new \RuntimeException("Stock insuffisant pour le produit {$product->name}.");
                    }
                }

                $order = Order::create([
                    'buyer_id' => Auth::id(),
                    'seller_id' => $sellerId,
                    'total_amount' => $total,
                    'currency' => 'XOF',
                    'status' => 'pending',
                    'payment_method' => $data['payment_method'],
                    'shipping_address' => $data['shipping_address'],
                    'shipping_status' => 'pending',
                ]);

                foreach ($quantitiesByProduct as $productId => $quantity) {
                    $product = $products[$productId];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'total_price' => $product->price * $quantity,
                    ]);

                    $product->decrement('stock', $quantity);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $total,
                    'method' => $data['payment_method'],
                    'provider' => $this->resolvePaymentProvider($data['payment_method']),
                    'status' => 'pending',
                ]);

                Shipment::create([
                    'order_id' => $order->id,
                    'status' => 'preparing',
                ]);

                return $order;
            });
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json($order->load(['items.product', 'payment', 'shipment']), 201);
    }

    private function resolvePaymentProvider(string $method): ?string
    {
        return match ($method) {
            'card' => 'stripe',
            'paypal' => 'paypal',
            'mobile_money' => 'flutterwave',
            default => null,
        };
    }
}
