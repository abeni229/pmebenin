<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ($user->role !== 'admin' && $user->role !== 'seller')) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Accès réservé aux administrateurs ou aux vendeurs approuvés.'], 403);
            }

            abort(403);
        }

        if ($user->role === 'seller' && $user->seller_status !== 'approved') {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Vendeur non approuvé.'], 403);
            }

            abort(403);
        }

        return $next($request);
    }
}
