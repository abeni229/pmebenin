<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        if ($user->role === 'seller') {
            return $this->sellerDashboard($user);
        }

        return $this->buyerDashboard($user);
    }

    protected function adminDashboard()
    {
        return view('dashboard.admin', [
            'totalUsers' => User::count(),
            'buyers' => User::where('role', 'buyer')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'pendingSellers' => User::where('role', 'seller')->where('seller_status', 'pending')->count(),
            'pendingProducts' => Product::where('quality_status', 'pending')->count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'pendingShipments' => Shipment::whereIn('status', ['pending', 'preparing', 'shipped'])->count(),
            'totalCommission' => Payment::sum('commission_amount'),
            'recentSellers' => User::where('role', 'seller')->latest()->limit(5)->get(),
        ]);
    }

    protected function sellerDashboard(User $seller)
    {
        return view('dashboard.seller', [
            'productCount' => $seller->products()->count(),
            'orderCount' => Order::where('seller_id', $seller->id)->count(),
            'pendingOrders' => Order::where('seller_id', $seller->id)->where('status', 'pending')->count(),
            'salesAmount' => Order::where('seller_id', $seller->id)->sum('total_amount'),
            'latestProducts' => $seller->products()->latest()->limit(5)->get(),
            'orders' => Order::with(['items.product', 'buyer', 'shipment'])
                ->where('seller_id', $seller->id)
                ->orderByDesc('created_at')
                ->get(),
            'categories' => \App\Models\Category::orderBy('name')->get(),
        ]);
    }

    protected function buyerDashboard(User $buyer)
    {
        return view('dashboard.buyer', [
            'orderCount' => $buyer->orders()->count(),
            'wishlistCount' => Wishlist::where('user_id', $buyer->id)->count(),
            'recentOrders' => $buyer->orders()->latest()->limit(5)->get(),
        ]);
    }
}
