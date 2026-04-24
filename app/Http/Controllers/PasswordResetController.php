<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function showResetForm()
    {
        return view('auth.password-reset');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(64);

        // Supprimer les tokens existants pour cet email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Créer un nouveau token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Envoyer l'email
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);
        
        try {
            Mail::send('emails.password-reset', [
                'user' => $user,
                'resetUrl' => $resetUrl,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Réinitialisation de mot de passe - PME Bénin');
            });
        } catch (\Exception $e) {
            // Si l'email échoue, afficher quand même le lien (pour développement)
            return back()->with('reset-link', $resetUrl)->with('status', 'Un lien de réinitialisation a été envoyé.');
        }

        return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
    }

    public function showNewPasswordForm($token, $email)
    {
        $passwordToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$passwordToken || !Hash::check($token, $passwordToken->token)) {
            abort(403, 'Lien de réinitialisation invalide ou expiré.');
        }

        // Vérifier si le token a plus de 2 heures
        if (now()->diffInMinutes($passwordToken->created_at) > 120) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            abort(403, 'Lien de réinitialisation expiré. Veuillez refaire une demande.');
        }

        return view('auth.new-password', ['token' => $token, 'email' => $email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $passwordToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordToken || !Hash::check($request->token, $passwordToken->token)) {
            return back()->withErrors(['email' => 'Lien de réinitialisation invalide.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('status', 'Votre mot de passe a été réinitialisé. Veuillez vous connecter.');
    }
}
