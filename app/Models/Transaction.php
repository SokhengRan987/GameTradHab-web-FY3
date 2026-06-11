<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const STATUS_PENDING   = 'pending';
    const STATUS_PAID      = 'paid';
    const STATUS_ESCROW    = 'escrow';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REFUNDED  = 'refunded';

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
        'payment_reference',
        'bank_name',
        'bank_account_name',
        // 'buyer_paid_at',
        'admin_confirmed_at',
        'payment_note',
    ];

    protected $casts = [
        'amount'             => 'decimal:2',
        'platform_fee'       => 'decimal:2',
        'seller_payout'      => 'decimal:2',
        'review_deadline'    => 'datetime',
        'buyer_confirmed_at' => 'datetime',
        'escrow_released_at' => 'datetime',
        'buyer_paid_at' => 'datetime',
        // 'admin_paid_at' => 'datetime',
        'admin_confirmed_at' => 'datetime',
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

    // Review left for this transaction
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Has the buyer left a review yet?
    public function hasReview(): bool
    {
        return $this->review()->exists();
    }

    // Is waiting for buyer to pay?
    public function isPendingPayment(): bool {
        return $this->status === 'pending';
    }

    // Has buyer clicked "I have paid" ?
    public function isBuyerPaid(): bool{
        return $this->status === 'paid';
    }

    // Has admin confirmed payment?
    public function isEscrow(): bool {
        return $this->status === 'escrow';
    }
}
