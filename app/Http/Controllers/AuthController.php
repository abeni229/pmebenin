<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/',
            ],
            'role' => ['required', 'in:buyer,seller'],
            'phone' => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:255'],
        ], [
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'location' => $data['location'] ?? null,
            'seller_status' => $data['role'] === 'seller' ? 'pending' : 'approved',
        ]);

        Auth::login($user);
        $user->sendEmailVerificationNotification();
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json(['user' => $user], 201);
        }

        return redirect('/email/verify')->with('status', 'Un email de confirmation a été envoyé. Vérifiez votre boîte de réception.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->blocked_until && now()->lt($user->blocked_until)) {
            $minutes = now()->diffInMinutes($user->blocked_until) ?: 1;
            $message = 'Compte bloqué. Réessayez dans ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') . '.';

            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 423);
            }

            return back()->withErrors(['email' => $message])->withInput();
        }

        if (! Auth::attempt($credentials)) {
            if ($user) {
                $user->failed_login_attempts = $user->failed_login_attempts + 1;

                if ($user->failed_login_attempts >= 3) {
                    $user->blocked_until = now()->addMinutes(15);
                }

                $user->save();
            }

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Identifiants invalides.'], 401);
            }

            return back()->withErrors(['email' => 'Identifiants invalides.'])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $user->failed_login_attempts = 0;
        $user->blocked_until = null;
        $user->save();

        if ($user->requiresEmailVerification()) {
            return redirect('/email/verify')->with('status', 'Veuillez confirmer votre adresse email avant d accéder au dashboard.');
        }

        if ($request->wantsJson()) {
            return response()->json(['user' => $user]);
        }

        return redirect('/dashboard')->with('status', 'Connexion réussie.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Déconnexion réussie.']);
        }

        return redirect('/')->with('status', 'Déconnexion réussie.');
    }

    public function verificationNotice()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect('/dashboard')->with('status', 'Adresse email vérifiée.');
    }

    public function resendVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Un nouveau lien de confirmation a été envoyé.');
    }

    public function approveSeller(User $seller)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        if ($seller->role !== 'seller') {
            return response()->json(['message' => 'Utilisateur non vendeur.'], 422);
        }

        $seller->seller_status = 'approved';
        $seller->save();

        return response()->json(['seller' => $seller]);
    }
}
