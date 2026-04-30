<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Withdrawal extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'method', 'account_details', 'status', 'admin_note',
    ];
 
    protected $casts = ['amount' => 'decimal:2'];
 
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
