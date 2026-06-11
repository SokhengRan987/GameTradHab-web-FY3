<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EscrowLog extends Model
{
    const STATUS_HELD     = 'held';
    const STATUS_RELEASED = 'released';
    const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'transaction_id',
        'held_amount',
        'status',
        'released_by',
        'held_at',
        'released_at',
    ];

    protected $casts = [
        'held_amount' => 'decimal:2',
        'held_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    // This escrow log belongs to a transaction
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function isHeld(): bool
    {
        return $this->status === self::STATUS_HELD;
    }

    public function isReleased(): bool
    {
        return $this->status === self::STATUS_RELEASED;
    }

    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }
}
