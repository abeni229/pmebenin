<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string'],
        ]);

        $review = Review::create([
            'product_id' => $data['product_id'],
            'user_id' => Auth::id(),
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return $review->load('user');
    }
}
