<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = [
        'transaction_id',
        'reviewer_id',
        'seller_id',
        'listing_id',
        'rating',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
    ];

    // The transaction this review belongs to
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    // The buyer who wrote the review
    public function reviewer(){
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // The selller being reviewed
    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }
    // The listings that was purchased
    public function listing(){
        return $this->belongsTo(Listing::class);
    }

    // Star display helper
    public function stars(): string
    {
        return str_repeat('⭐', $this->rating)
             . str_repeat('☆', 5 - $this->rating);
    }

}
