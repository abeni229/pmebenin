<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerifiedOrLegacy
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->requiresEmailVerification()) {
            if ($request->expectsJson()) {
                abort(403, 'Adresse email non vérifiée.');
            }

            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
