<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public const METHODS = [
        'card',
        'mobile_money',
        'paypal',
    ];

    protected $fillable = [
        'order_id',
        'amount',
        'commission_amount',
        'method',
        'status',
        'provider',
        'transaction_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public static function allowedMethods(): array
    {
        return self::METHODS;
    }

    public static function allowedMethodsList(): string
    {
        return implode(',', self::METHODS);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
