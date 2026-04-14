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
        return Order::with(['items.product', 'seller', 'payment', 'shipment'])
            ->where('buyer_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
    }

    public function sellerOrders()
    {
        return Order::with(['items.product', 'buyer', 'payment', 'shipment'])
            ->where('seller_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
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
