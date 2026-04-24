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
        return Product::with(['seller', 'category'])
            ->where('quality_status', 'pending')
            ->orderByDesc('created_at')
            ->get();
    }

    public function approveProductQuality(Product $product)
    {
        $product->quality_status = 'approved';
        $product->is_active = true;
        $product->save();

        return $product->load(['seller', 'category']);
    }

    public function rejectProductQuality(Product $product)
    {
        $product->quality_status = 'rejected';
        $product->is_active = false;
        $product->save();

        return $product->load(['seller', 'category']);
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
            'carrier' => ['sometimes', 'nullable', 'string', 'max:255'],
            'tracking_number' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', Rule::in(['preparing', 'shipped', 'delivered', 'cancelled'])],
            'estimated_delivery_date' => ['sometimes', 'nullable', 'date'],
        ]);

        $shipment->update($data);

        return $shipment->load(['order.buyer', 'order.seller']);
    }

    public function updatePayment(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
            'commission_amount' => ['sometimes', 'numeric', 'min:0'],
            'transaction_reference' => ['sometimes', 'nullable', 'string', 'max:255'],
            'provider' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $payment->update($data);

        return $payment->load('order');
    }

    public function pendingSellers()
    {
        return User::where('role', 'seller')
            ->where('seller_status', 'pending')
            ->orderByDesc('created_at')
            ->get();
    }

    public function approveSeller(User $seller)
    {
        if ($seller->role !== 'seller') {
            return response()->json(['message' => 'Utilisateur non vendeur.'], 422);
        }

        $seller->seller_status = 'approved';
        $seller->save();

        return $seller;
    }

    public function rejectSeller(User $seller)
    {
        if ($seller->role !== 'seller') {
            return response()->json(['message' => 'Utilisateur non vendeur.'], 422);
        }

        $seller->seller_status = 'rejected';
        $seller->save();

        return $seller;
    }

    public function deleteSeller(User $seller)
    {
        if ($seller->role !== 'seller') {
            return response()->json(['message' => 'Utilisateur non vendeur.'], 422);
        }

        $seller->delete();

        return response()->json(['message' => 'Vendeur supprimé.']);
    }
}
