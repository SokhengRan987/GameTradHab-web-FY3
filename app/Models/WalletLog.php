<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletLog extends Model
{
    //
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_after',
        'reference',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    // This wallet log belongs to a user
    public function user(){
        return $this->belongsTo(User::class);
    }

}
