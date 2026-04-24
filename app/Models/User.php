<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use MustVerifyEmail;
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'seller_status',
        'location',
        'bio',
        'skip_email_verification',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'failed_login_attempts' => 'int',
            'blocked_until' => 'datetime',
            'skip_email_verification' => 'boolean',
        ];
    }

    public function requiresEmailVerification(): bool
    {
        return $this->role !== 'admin'
            && ! $this->skip_email_verification
            && ! $this->hasVerifiedEmail();
    }

    public static array $sellerStatusLabels = [
        'pending' => 'En attente',
        'approved' => 'Approuvé',
        'rejected' => 'Rejeté',
    ];

    public function getSellerStatusLabelAttribute(): string
    {
        return self::$sellerStatusLabels[$this->seller_status] ?? ucfirst($this->seller_status);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
}
