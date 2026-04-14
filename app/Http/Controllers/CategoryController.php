<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::query()->withCount('products')->orderBy('name')->get();
    }

    public function show(Category $category)
    {
        return $category->load(['products' => function ($query) {
            $query->where('is_active', true);
        }, 'products.seller']);
    }
}
