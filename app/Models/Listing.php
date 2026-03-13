<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    //
    protected $fillable = [
        'user_id',
        'game_id',
        'title',
        'description',
        'price',
        'rank',
        'level',
        'server',
        'platform',
        'account_age',
        'heroes_skins',
        'tags',
        'type',
        'status',
        'admin_notes',
        'is_featured',
        'views_count',
    ];

    protected $casts = [
        'heroes_skins' => 'array',
        'tags'         => 'array',
        'is_featured'  => 'boolean',
        'price'        => 'decimal:2',
    ];
    // This listing belongs to a seller (User)
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // This listing belongs to a game
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    // This listing has many images
    public function images()
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }
    // Get only the first image (for card thumbnails)
    public function firstImage()
    {
        return $this->hasOne(ListingImage::class)->orderBy('sort_order');
    }
    // This listing has many transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // ── Scopes (reusable query filters) ──────────
    // Use: Listing::active()->get()
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Use: Listing::featured()->get()
    public function scopeFeatured($query){
        return $query->where('is_featured', true);
    }
    // Use: Listing::fixedPrice()->get()
    public function scopeFixedPrice($query){
        return $query->where('type', 'fixed');
    }

    //Increment view count when someone visits
    public function incrementViews(){
        $this->increment('views_count');
    }

}
