<?php
// ─── app/Models/Wallet.php ────────────────────────────────────────

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance', 'pending_balance'];

    protected $casts = [
        'balance'         => 'decimal:2',
        'pending_balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Créditer le wallet d'un montant (solde disponible).
     */
    public function credit(float $amount, string $description, ?int $orderId = null): WalletTransaction
    {
        $this->increment('balance', $amount);

        return $this->transactions()->create([
            'order_id'    => $orderId,
            'type'        => 'credit',
            'amount'      => $amount,
            'description' => $description,
            'status'      => 'completed',
        ]);
    }

    /**
     * Débiter le wallet (retrait ou commission).
     */
    public function debit(float $amount, string $type, string $description, ?int $orderId = null): WalletTransaction
    {
        $this->decrement('balance', $amount);

        return $this->transactions()->create([
            'order_id'    => $orderId,
            'type'        => $type,
            'amount'      => $amount,
            'description' => $description,
            'status'      => 'completed',
        ]);
    }
}