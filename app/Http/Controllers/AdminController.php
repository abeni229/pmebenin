<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function pendingProducts()
    {
        return response()->json(
            Product::with(['seller', 'category'])
                ->where('quality_status', 'pending')
                ->orderByDesc('created_at')
                ->get()
        );
    }

    public function approveProductQuality(Product $product)
    {
        $product->quality_status = 'approved';
        $product->is_active      = true;
        $product->save();

        // CORRECTION : retourner explicitement response()->json()
        // pour que le fetch puisse lire product.quality_status et confirmer le succès
        return response()->json($product->load(['seller', 'category']));
    }

    public function rejectProductQuality(Product $product)
    {
        $product->quality_status = 'rejected';
        $product->is_active      = false;
        $product->save();

        return response()->json($product->load(['seller', 'category']));
    }

    public function pendingSellers()
    {
        return response()->json(
            User::where('role', 'seller')
                ->where('seller_status', 'pending')
                ->orderByDesc('created_at')
                ->get()
        );
    }

    public function approveSeller(User $seller)
    {
        if ($seller->role !== 'seller') {
            return response()->json(['message' => 'Utilisateur non vendeur.'], 422);
        }

        $seller->seller_status = 'approved';
        $seller->save();

        return response()->json($seller);
    }

    public function rejectSeller(User $seller)
    {
        if ($seller->role !== 'seller') {
            return response()->json(['message' => 'Utilisateur non vendeur.'], 422);
        }

        $seller->seller_status = 'rejected';
        $seller->save();

        return response()->json($seller);
    }

    public function deleteSeller(User $seller)
    {
        if ($seller->role !== 'seller') {
            return response()->json(['message' => 'Utilisateur non vendeur.'], 422);
        }

        $seller->delete();

        return response()->json(['message' => 'Vendeur supprimé.']);
    }

    public function orders()
    {
        return Order::with(['buyer', 'seller', 'items.product', 'payment', 'shipment'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function shipments()
    {
        return Shipment::with(['order.buyer', 'order.seller'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function updateShipment(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'carrier'                => ['sometimes', 'nullable', 'string', 'max:255'],
            'tracking_number'        => ['sometimes', 'nullable', 'string', 'max:255'],
            'status'                 => ['sometimes', 'string', Rule::in(['preparing', 'shipped', 'delivered', 'cancelled'])],
            'estimated_delivery_date'=> ['sometimes', 'nullable', 'date'],
        ]);

        $shipment->update($data);

        return $shipment->load(['order.buyer', 'order.seller']);
    }

    public function updatePayment(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'status'                => ['sometimes', 'string', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
            'commission_amount'     => ['sometimes', 'numeric', 'min:0'],
            'transaction_reference' => ['sometimes', 'nullable', 'string', 'max:255'],
            'provider'              => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $payment->update($data);

        return $payment->load('order');
    }
}