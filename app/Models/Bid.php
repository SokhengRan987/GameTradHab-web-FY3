<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    //
    protected $fillable = [
        'listing_id',
        'user_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // This listing this bid belongs to
    public function listing(){
        return $this->belongsTo(Listing::class);
    }

    // Is this bid currently winning?
    public function isActive(): bool {
        return $this->status === 'active';
    }

    // Did this bid win the auction?
    public function isWon(): bool {
        return $this->status === 'won';
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
