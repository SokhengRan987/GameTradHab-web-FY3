<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EscrowLog extends Model
{
    //
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
}
