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
        // Adding this for scope auction
        // =============================
        'starting_price',
        'bid_increment',
        'auction_ends_at',
        'current_bid',
        'highest_bidder_id',
        // =============================
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
        // =============================
        // Adding new for seller form
        // =============================
        'seller_phone',
        'seller_country',
        'stock_source',
        'stock_source_note',
        'is_flagged',
        'flag_reason',
        'flagged_at',
    ];

    protected $casts = [
        'heroes_skins' => 'array',
        'tags'         => 'array',
        'is_featured'  => 'boolean',
        'price'        => 'decimal:2',
        'is_flagged' => 'boolean',
        'flagged_at' => 'datetime',

        // Adding this for scope auction
        // =============================
        'starting_price' => 'decimal:2',
        'bid_increment' => 'decimal:2',
        'current_bid' => 'decimal:2',
        'auction_ends_at' => 'datetime',
        // =============================
    ];


    // ===========================================
    // New Relationship in scope Auction
    // ===========================================
    // All bids on this listing
    public function bids(){
        return $this->hasMany(Bid::class)->orderBy('amount', 'desc');
    }

    // The current highest bidder
    public function highestBidder()
    {
        return $this->belongsTo(User::class, 'highest_bidder_id');
    }

    // <<<< Auction helper methods >>>>>>>>>>>>>>>>>>>>>>>>>>
    // Is this an auction listing?
    public function isAuction(): bool{
        return $this->type === 'auction';
    }

    // Is this a fixed price listing?
    public function isFixed():bool {
        return $this->type === 'fixed';
    }

    // Has the auction ended?
    public function hasEnded(): bool {
        return $this->auction_ends_at && $this->auction_ends_at->isPast();
    }

    // Is the auction still running?
    public function isLive(): bool {
        return $this->isAuction()
            && $this->status === 'active'
            && !$this->hasEnded();
    }

    // What is the minimum next bid?
    public function minimumNextBid(): float {
        if ($this->current_bid){
            return (float) $this->current_id + (float) $this->bid_increment;
        }
        return (float) $this->starting_price;
    }

    // How much time is left in the auction?
    public function timeRemaining(): string {
        if(!$this->auction_ends_at || $this->hasEnded()){
            return 'Ended';
        }
        return $this->auction_ends_at->diffForHumans();
    }

    // Scope for active auctions only
    public function scopeAuction($query) {
        return $query->where('type', 'auction')
                     ->where('status', 'active')
                     ->where('aution_ends_at', '>', now());
    }
    // ===========================================

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

    // Reports on this listing
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Scope — flagged listings only
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true)
                    ->where('status', 'active');
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
