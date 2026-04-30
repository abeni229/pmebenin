<?php
/*
|=============================================================================
| GUIDE SANDBOX PAIEMENT — PME Bénin
| Provider : Flutterwave (adapté Bénin/Afrique de l'Ouest, XOF supporté)
|=============================================================================
|
| ÉTAPE 1 — Créer un compte Flutterwave sandbox
| ──────────────────────────────────────────────
| 1. Aller sur https://app.flutterwave.com/register
| 2. Créer un compte gratuit
| 3. Dans le tableau de bord → passer en mode "Test" (toggle en haut à droite)
| 4. Récupérer dans Settings > API Keys :
|      FLW_PUBLIC_KEY=FLWPUBK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X
|      FLW_SECRET_KEY=FLWSECK_TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-X
|
| ÉTAPE 2 — Ajouter les variables dans .env
| ──────────────────────────────────────────
|   FLW_PUBLIC_KEY=FLWPUBK_TEST-votre-cle-publique
|   FLW_SECRET_KEY=FLWSECK_TEST-votre-cle-secrete
|   FLW_ENCRYPTION_KEY=votre-cle-encryption        (dans Settings > API Keys)
|   FLW_WEBHOOK_HASH=un-secret-que-vous-inventez   (ex: pmebenin_webhook_2026)
|   APP_URL=http://localhost:8000                   (votre URL locale)
|
| ÉTAPE 3 — Cartes de test Flutterwave
| ─────────────────────────────────────
|   Carte qui RÉUSSIT :
|     Numéro  : 4187 4274 1556 4246
|     Expiry  : 09/32
|     CVV     : 828
|     PIN     : 3310
|     OTP     : 12345
|
|   Carte qui ÉCHOUE (pour tester les erreurs) :
|     Numéro  : 5531 8866 5214 2950
|     Expiry  : 09/32
|     CVV     : 564
|     PIN     : 3310
|     OTP     : 12345
|
|   Mobile Money test (MTN Bénin) :
|     Numéro  : 0551234598
|     OTP     : 123456
|
| ÉTAPE 4 — Installer le SDK Flutterwave
| ───────────────────────────────────────
|   composer require flutterwavedev/flutterwave-v3
|
|=============================================================================
*/

// ─────────────────────────────────────────────────────────────────────────────
// app/Http/Controllers/PaymentController.php  (NOUVEAU FICHIER)
// ─────────────────────────────────────────────────────────────────────────────

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->publicKey = config('services.flutterwave.public_key');
    }

    /**
     * Initialiser un paiement Flutterwave et rediriger vers la page de paiement.
     * Appelé depuis le checkout après validation du formulaire.
     */
    public function initiate(Request $request, Order $order)
    {
        $user = Auth::user();

        // Sécurité : seul l'acheteur de la commande peut payer
        if ($order->buyer_id !== $user->id) {
            abort(403);
        }

        if ($order->payment?->status === 'paid') {
            return redirect('/orders')->with('status', 'Cette commande a déjà été payée.');
        }

        $txRef = 'PME-' . $order->id . '-' . Str::upper(Str::random(8));

        // Sauvegarder la référence de transaction
        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            ['transaction_reference' => $txRef]
        );

        // Payload Flutterwave
        $payload = [
            'tx_ref'          => $txRef,
            'amount'          => (float) $order->total_amount,
            'currency'        => $order->currency ?? 'XOF',
            'redirect_url'    => route('payment.callback'),
            'payment_options' => 'card,mobilemoneyfrancophone',
            'customer' => [
                'email'       => $user->email,
                'name'        => $user->name,
                'phonenumber' => $user->phone ?? '',
            ],
            'customizations' => [
                'title'       => 'PME Bénin',
                'description' => "Commande #{$order->id}",
                'logo'        => asset('images/logo.png'),
            ],
            'meta' => [
                'order_id'  => $order->id,
                'buyer_id'  => $user->id,
            ],
        ];

        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/payments", $payload);

        if (! $response->successful() || $response->json('status') !== 'success') {
            Log::error('Flutterwave initiate failed', $response->json());
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement. Réessayez.');
        }

        // Rediriger vers la page de paiement Flutterwave
        return redirect($response->json('data.link'));
    }

    /**
     * Callback après paiement (Flutterwave redirige ici avec ?status=&tx_ref=&transaction_id=)
     */
    public function callback(Request $request)
    {
        $status        = $request->query('status');
        $txRef         = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');

        if ($status !== 'successful' || ! $txRef || ! $transactionId) {
            return redirect('/orders')->with('error', 'Paiement annulé ou échoué.');
        }

        // Vérifier la transaction auprès de Flutterwave (NE PAS faire confiance au callback seul)
        $verify = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

        if (! $verify->successful()) {
            Log::error('Flutterwave verify failed', ['tx_id' => $transactionId]);
            return redirect('/orders')->with('error', 'Impossible de vérifier le paiement. Contactez le support.');
        }

        $data = $verify->json('data');

        // Vérifications de sécurité
        $payment = Payment::where('transaction_reference', $txRef)->first();

        if (! $payment) {
            Log::warning('Payment not found for tx_ref', ['tx_ref' => $txRef]);
            return redirect('/orders')->with('error', 'Référence de transaction introuvable.');
        }

        $order = $payment->order;

        // Vérifier montant et devise
        if (
            (float) $data['amount']   !== (float) $order->total_amount ||
            $data['currency']         !== ($order->currency ?? 'XOF') ||
            $data['status']           !== 'successful'
        ) {
            Log::warning('Flutterwave payment mismatch', [
                'expected_amount'   => $order->total_amount,
                'received_amount'   => $data['amount'],
                'expected_currency' => $order->currency,
                'received_currency' => $data['currency'],
            ]);
            return redirect('/orders')->with('error', 'Données de paiement invalides. Contactez le support.');
        }

        // Mettre à jour le paiement et la commande
        $payment->update([
            'status'                => 'paid',
            'transaction_reference' => $transactionId,
            'provider'              => 'flutterwave',
        ]);

        $order->update(['status' => 'confirmed']);

        return redirect('/orders')->with('status', "Paiement confirmé ! Commande #{$order->id} validée.");
    }

    /**
     * Webhook Flutterwave (pour les notifications serveur-à-serveur)
     * Route : POST /payment/webhook  (sans middleware auth ni csrf)
     */
    public function webhook(Request $request)
    {
        // Vérifier la signature du webhook
        $hash = $request->header('verif-hash');
        if ($hash !== config('services.flutterwave.webhook_hash')) {
            abort(401);
        }

        $payload = $request->json()->all();
        $event   = $payload['event'] ?? null;

        if ($event === 'charge.completed' && ($payload['data']['status'] ?? null) === 'successful') {
            $transactionId = $payload['data']['id'];
            $txRef         = $payload['data']['tx_ref'];

            $payment = Payment::where('transaction_reference', $txRef)
                ->orWhere('transaction_reference', (string) $transactionId)
                ->first();

            if ($payment && $payment->status !== 'paid') {
                $payment->update([
                    'status'                => 'paid',
                    'transaction_reference' => (string) $transactionId,
                ]);
                $payment->order?->update(['status' => 'confirmed']);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}