<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    const COMMISSION_RATE = 0.08; // 8% à l'admin

    // ─── Panier ──────────────────────────────────────────────────

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('cart', [
            'cart'  => $cart,
            'total' => collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']),
        ]);
    }

    public function addToCart(Request $request, Product $product)
    {
        if (!$product->is_active || $product->stock <= 0) {
            return back()->with('status', 'Ce produit est en rupture de stock.');
        }

        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart     = session()->get('cart', []);

        $current = $cart[$product->id] ?? [
            'id'          => $product->id,
            'name'        => $product->name,
            'price'       => $product->price,
            'currency'    => $product->currency,
            'image'       => $product->image,
            'category'    => $product->category->name ?? 'Produit',
            'seller_id'   => $product->seller_id,
            'seller_name' => $product->seller->name ?? 'Vendeur local',
            'quantity'    => 0,
        ];

        $current['quantity'] = min($product->stock, $current['quantity'] + $quantity);
        $cart[$product->id]  = $current;
        session()->put('cart', $cart);

        return back()->with('status', 'Produit ajouté au panier.');
    }

    public function removeFromCart(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);
        return back()->with('status', 'Produit retiré du panier.');
    }

    public function updateCart(Request $request)
    {
        $data = $request->validate([
            'items'                => ['required', 'array'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
            'redirect_to_checkout' => ['sometimes', 'nullable'],
        ]);

        $cart = session()->get('cart', []);

        foreach ($data['items'] as $productId => $item) {
            if (!isset($cart[$productId])) continue;
            $product  = Product::find($productId);
            $maxStock = $product ? $product->stock : 1;
            $cart[$productId]['quantity'] = max(1, min((int) $item['quantity'], $maxStock));
        }

        session()->put('cart', $cart);

        if ($request->input('redirect_to_checkout')) {
            return redirect()->route('checkout');
        }

        return back()->with('status', 'Panier mis à jour.');
    }

    // ─── Checkout ────────────────────────────────────────────────

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('status', 'Votre panier est vide.');
        }

        // Grouper les articles par vendeur pour l'affichage
        $byVendor    = collect($cart)->groupBy('seller_id');
        $isMulti     = $byVendor->count() > 1;

        return view('checkout', [
            'cart'           => $cart,
            'byVendor'       => $byVendor,
            'isMulti'        => $isMulti,
            'total'          => collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']),
            'paymentMethods' => Payment::allowedMethods(),
        ]);
    }

    /**
     * Passer UNE commande groupée (multi-vendeurs).
     * Crée une Order par vendeur, distribue les montants,
     * prélève 8% de commission pour l'admin.
     */
    public function placeOrder(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'buyer') {
            return redirect()->back()->with('error', 'Seuls les acheteurs peuvent passer une commande.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Votre panier est vide.');
        }

        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:1024'],
            'payment_method'   => ['required', 'string', Rule::in(Payment::allowedMethods())],
            'seller_id'        => ['sometimes', 'nullable', 'integer'], // pour "payer un seul vendeur"
        ]);

        $items      = collect($cart);
        $productIds = $items->pluck('id')->toArray();

        $products = Product::whereIn('id', $productIds)
            ->where('is_active', true)
            ->with('seller')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        // Vérifications stock
        foreach ($items as $item) {
            $product = $products[$item['id']] ?? null;
            if (!$product) {
                return redirect()->route('cart')->with('error', "Produit introuvable ou désactivé.");
            }
            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart')->with('error', "Stock insuffisant pour {$product->name}.");
            }
        }

        // Si seller_id précisé → payer uniquement les articles de ce vendeur
        $filterSellerId = $data['seller_id'] ?? null;
        if ($filterSellerId) {
            $items    = $items->filter(fn($i) => $i['seller_id'] == $filterSellerId);
            $products = $products->filter(fn($p) => $p->seller_id == $filterSellerId);
        }

        // Grouper par vendeur
        $byVendor = $items->groupBy('seller_id');

        $orders = DB::transaction(function () use ($user, $data, $byVendor, $products) {
            $createdOrders = [];
            $adminWallet   = $this->getOrCreateWallet(User::where('role', 'admin')->first()?->id);

            foreach ($byVendor as $sellerId => $sellerItems) {
                $sellerTotal     = $sellerItems->sum(fn($i) => $i['price'] * $i['quantity']);
                $commissionAmt   = round($sellerTotal * self::COMMISSION_RATE, 2);
                $sellerNet       = $sellerTotal - $commissionAmt;

                $order = Order::create([
                    'buyer_id'         => $user->id,
                    'seller_id'        => $sellerId,
                    'total_amount'     => $sellerTotal,
                    'currency'         => 'XOF',
                    'status'           => 'pending',
                    'payment_method'   => $data['payment_method'],
                    'shipping_address' => $data['shipping_address'],
                    'shipping_status'  => 'pending',
                    'is_multi_vendor'  => $byVendor->count() > 1,
                    'commission_rate'  => self::COMMISSION_RATE * 100,
                ]);

                foreach ($sellerItems as $item) {
                    $product = $products[$item['id']];
                    OrderItem::create([
                        'order_id'    => $order->id,
                        'product_id'  => $product->id,
                        'quantity'    => $item['quantity'],
                        'unit_price'  => $product->price,
                        'total_price' => $product->price * $item['quantity'],
                    ]);
                    $product->decrement('stock', $item['quantity']);
                }

                Payment::create([
                    'order_id'          => $order->id,
                    'amount'            => $sellerTotal,
                    'commission_amount' => $commissionAmt,
                    'method'            => $data['payment_method'],
                    'provider'          => $this->resolvePaymentProvider($data['payment_method']),
                    'status'            => 'pending',
                ]);

                Shipment::create([
                    'order_id' => $order->id,
                    'status'   => 'preparing',
                ]);

                // ── Distribution wallet (après confirmation paiement Flutterwave)
                // Pour l'instant on crédite en "pending" — libéré au callback Flutterwave
                $sellerWallet = $this->getOrCreateWallet($sellerId);
                $sellerWallet->increment('pending_balance', $sellerNet);
                $sellerWallet->transactions()->create([
                    'order_id'    => $order->id,
                    'type'        => 'credit',
                    'amount'      => $sellerNet,
                    'description' => "Vente commande #{$order->id} (net après 8% commission)",
                    'status'      => 'pending',
                ]);

                if ($adminWallet) {
                    $adminWallet->increment('pending_balance', $commissionAmt);
                    $adminWallet->transactions()->create([
                        'order_id'    => $order->id,
                        'type'        => 'commission',
                        'amount'      => $commissionAmt,
                        'description' => "Commission 8% commande #{$order->id}",
                        'status'      => 'pending',
                    ]);
                }

                $createdOrders[] = $order;
            }

            return $createdOrders;
        });

        session()->forget('cart');

        if ($request->wantsJson()) {
            return response()->json(['orders' => $orders], 201);
        }

        $count = count($orders);
        return redirect('/orders')->with('status',
            $count > 1
                ? "{$count} commandes créées auprès de {$count} vendeurs. Suivez-les depuis votre espace."
                : "Commande créée. Suivez sa livraison depuis votre espace commande."
        );
    }

    // ─── Wallet helpers ──────────────────────────────────────────

    private function getOrCreateWallet(?int $userId): ?Wallet
    {
        if (!$userId) return null;
        return Wallet::firstOrCreate(['user_id' => $userId], ['balance' => 0, 'pending_balance' => 0]);
    }

    /**
     * Libérer les fonds en attente après confirmation Flutterwave.
     * Appelé depuis PaymentController@callback.
     */
    public static function releaseWalletFunds(Order $order): void
    {
        $payment = $order->payment;
        if (!$payment || $payment->status !== 'paid') return;

        $commissionAmt = (float) $payment->commission_amount;
        $sellerNet     = (float) $payment->amount - $commissionAmt;

        DB::transaction(function () use ($order, $sellerNet, $commissionAmt) {
            // Vendeur
            $sw = Wallet::firstOrCreate(['user_id' => $order->seller_id]);
            $sw->decrement('pending_balance', $sellerNet);
            $sw->increment('balance', $sellerNet);
            $sw->transactions()
               ->where('order_id', $order->id)
               ->where('status', 'pending')
               ->update(['status' => 'completed']);

            // Admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $aw = Wallet::firstOrCreate(['user_id' => $admin->id]);
                $aw->decrement('pending_balance', $commissionAmt);
                $aw->increment('balance', $commissionAmt);
                $aw->transactions()
                   ->where('order_id', $order->id)
                   ->where('status', 'pending')
                   ->update(['status' => 'completed']);
            }
        });
    }

    // ─── Demande de retrait (vendeur) ────────────────────────────

    public function requestWithdrawal(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'amount'          => ['required', 'numeric', 'min:1000'],
            'method'          => ['required', 'in:mobile_money,bank'],
            'account_details' => ['required', 'string', 'max:255'],
        ]);

        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $data['amount']) {
            return back()->with('error', 'Solde insuffisant pour effectuer ce retrait.');
        }

        DB::transaction(function () use ($user, $data, $wallet) {
            // Bloquer le montant pendant le traitement
            $wallet->decrement('balance', $data['amount']);
            $wallet->increment('pending_balance', $data['amount']);

            \App\Models\Withdrawal::create([
                'user_id'         => $user->id,
                'amount'          => $data['amount'],
                'method'          => $data['method'],
                'account_details' => $data['account_details'],
                'status'          => 'pending',
            ]);

            $wallet->transactions()->create([
                'type'        => 'withdrawal',
                'amount'      => $data['amount'],
                'description' => "Demande de retrait {$data['method']}",
                'status'      => 'pending',
            ]);
        });

        return back()->with('status', 'Demande de retrait soumise. Elle sera traitée sous 24-48h.');
    }

    // ─── Reste du contrôleur (inchangé) ─────────────────────────

    public function index()
    {
        $orders = Order::with(['items.product', 'seller', 'payment', 'shipment'])
            ->where('buyer_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        if (request()->wantsJson()) return $orders;
        return view('orders', ['orders' => $orders]);
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
            'status' => ['required', Rule::in(['pending','confirmed','shipped','delivered','cancelled'])],
        ]);
        $order->update(['status' => $data['status']]);
        return redirect()->back()->with('status', 'Statut de commande mis à jour.');
    }

    public function updateShipping(Request $request, Order $order)
    {
        $user = Auth::user();
        if ($user->role !== 'seller' || $order->seller_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }
        $data = $request->validate([
            'shipping_status' => ['required', Rule::in(['pending','preparing','shipped','delivered','cancelled'])],
        ]);
        $order->update(['shipping_status' => $data['shipping_status']]);
        return redirect()->back()->with('status', 'État de livraison mis à jour.');
    }

    private function resolvePaymentProvider(string $method): ?string
    {
        return match ($method) {
            'card', 'mobile_money' => 'flutterwave',
            'paypal'               => 'paypal',
            default                => null,
        };
    }
}