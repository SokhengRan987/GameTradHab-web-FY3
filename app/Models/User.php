<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'wallet_balance',
        'rating_avg',
        'total_sales',
        'is_verified',
        'is_banned',
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
            'wallet_balance'    => 'decimal:2',
            'rating_avg'        => 'decimal:2',
            'is_verified'       => 'boolean',
            'is_banned'         => 'boolean',
        ];

    }
    // All listings this user posted as a seller
    public function listings()
    {
        return $this->hasMany(Listing::class, 'user_id');
    }
    // All purchases this user made as a buyer
    public function purchases(){
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    // All sales this user made as a seller
    public function sales(){
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    // All Wallet history logs
    public function walletLogs(){
        return $this->hasMany(WalletLog::class);
    }
    
    // ===============================================================

    // ── Role helpers ──────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }
}
