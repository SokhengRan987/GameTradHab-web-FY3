<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ListingImage extends Model
{
    //
    protected $fillable = [
        'listing_id',
        'image_path',
        'is_proof',
        'sort_order',
    ];

    protected $casts = [
        'is_proof' => 'boolean',
    ];

     // This image belongs to a listing
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    // Get the full URL of the image
    public function getUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

}
