<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'transaction_code',
        'listing_id',
        'buyer_id',
        'seller_id',
        'amount',
        'platform_fee',
        'seller_payout',
        'status',
        'payment_method',
        'review_deadline',
        'buyer_confirmed_at',
        'escrow_released_at',
    ];

    protected $casts = [
        'amount'             => 'decimal:2',
        'platform_fee'       => 'decimal:2',
        'seller_payout'      => 'decimal:2',
        'review_deadline'    => 'datetime',
        'buyer_confirmed_at' => 'datetime',
        'escrow_released_at' => 'datetime',
    ];
    // The listing being sold
    public function listing(){
        return $this->belongsTo(Listing::class);
    }

    // The person who bought
    public function buyer(){
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // The person who sold
    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }

    // The escrow record for this transaction
    public function escrowLog()
    {
        return $this->hasOne(EscrowLog::class);
    }

    //Auto-generate a unique transaction code
    // Ex output: TRX-20260312-5f2d3a1b4c6e7
    public static function generateCode(): string{
        return 'TXN-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}
