<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function shop(Request $request)
    {
        $products = $this->index($request);

        return view('shop', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'seller'])->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        return $query->orderByDesc('created_at')->paginate(12);
    }

    public function show(Product $product)
    {
        return $product->load(['category', 'seller', 'reviews.user']);
    }

    public function detail(Product $product)
    {
        return view('product', [
            'product' => $product->load(['category', 'seller', 'reviews.user']),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'seller' || $user->seller_status !== 'approved') {
            return response()->json(['message' => 'Vous devez être un vendeur approuvé pour ajouter un produit.'], 403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'currency' => ['sometimes', 'string', 'max:5'],
            'image' => ['nullable', 'url'],
        ]);

        $data['seller_id'] = $user->id;
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        $data['currency'] = $data['currency'] ?? 'XOF';

        $product = Product::create($data);

        if ($request->wantsJson()) {
            return $product;
        }

        return redirect()->route('dashboard')->with('status', 'Produit ajouté avec succès.');
    }

    public function update(Request $request, Product $product)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $product->seller_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'currency' => ['sometimes', 'string', 'max:5'],
            'image' => ['nullable', 'url'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $product->update($data);

        return $product;
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $product->seller_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Produit supprimé.']);
    }
}
