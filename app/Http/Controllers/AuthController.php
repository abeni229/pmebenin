<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:buyer,seller'],
            'phone' => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:255'],
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
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json(['user' => $user], 201);
        }

        return redirect('/')->with('status', 'Inscription réussie. Vous êtes connecté.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Identifiants invalides.'], 401);
            }

            return back()->withErrors(['email' => 'Identifiants invalides.'])->withInput();
        }

        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json(['user' => Auth::user()]);
        }

        return redirect('/')->with('status', 'Connexion réussie.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Déconnexion réussie.']);
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
