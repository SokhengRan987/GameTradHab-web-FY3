<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Review;

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
        'full_name',
        'country',
        'date_of_birth',
        'phone_country_code',
        'phone_number',
        'telegram',
        'whatsapp',
        'discord',
        'line_id',
        'profile_completed',
// ==============================================================
        'seller_onboarded',
        'seller_games',
        'seller_stock_source',
        'seller_sells_elsewhere',
        'seller_other_platforms',


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
            'date_of_birth'     => 'date',
            'profile_completed' => 'boolean',

            //
            'seller_onboarded'       => 'boolean',
            'seller_sells_elsewhere' => 'boolean',
        ];

    }
    public function hasCompletedOnboarding(): bool {
        return $this->seller_onboarded === true;
    }
    //================================================
    // Auction =======================================
    //================================================
    //Bid placed by this user
    public function bids(){
        return $this->hasMany(Bid::class);
    }
    // Auctions this user is currently winning
    public function winningBids(){
        return $this->hasMany(Bid::class)->where('status', 'active');
    }
    // =================================================

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
        // return $this->role === 'seller';
        return true;
    }

    public function isBuyer(): bool
    {
        //return $this->role === 'buyer';
        return true;
    }
// ===================================================================
    // Reviews this user has received as a seller
    public function reviews()
    {
        return $this->hasMany(Review::class, 'seller_id')
                    ->where('is_visible', true)
                    ->latest();
    }

    // Reviews this user has written as a buyer
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    // Recalculate and save rating average
    public function recalculateRating(): void
    {
        $avg = Review::where('seller_id', $this->id)
                    ->where('is_visible', true)
                    ->avg('rating');

        $this->update([
            'rating_avg' => $avg ? round($avg, 2) : 0,
        ]);
// ===================================================================

}
}
