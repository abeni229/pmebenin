<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        return Wishlist::with('product.category', 'product.seller')
            ->where('user_id', Auth::id())
            ->get();
    }

    public function toggle(Product $product)
    {
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        if (! $wishlist->wasRecentlyCreated) {
            $wishlist->delete();

            return response()->json(['message' => 'Produit retiré de la wishlist.']);
        }

        return response()->json(['message' => 'Produit ajouté à la wishlist.']);
    }
}
