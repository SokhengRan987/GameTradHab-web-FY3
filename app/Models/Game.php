<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'category',
        'platform',
        'banner_image',
        'icon_image',
        'rank_options',
        'server_options',
        'is_active',
    ];

    protected $casts = [
        'rank_options' => 'array',
        'server_options' => 'array',
        'is_active' => 'boolean',
    ];

    // One game has many listings
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
