<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\Game;
use App\Models\User;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::where('email', 'seller@test.com')->first();
        $mlbb   = Game::where('slug', 'mobile-legends')->first();
        $val    = Game::where('slug', 'valorant')->first();
        $pubg   = Game::where('slug', 'pubg-mobile')->first();

        $listings = [
            [
                'user_id'     => $seller->id,
                'game_id'     => $mlbb->id,
                'title'       => 'Mythic Account | 150 Skins | All Heroes',
                'description' => 'Selling my main Mythic account. All heroes unlocked, 150+ skins. No ban history.',
                'price'       => 120.00,
                'rank'        => 'Mythic',
                'level'       => 120,
                'server'      => 'SEA',
                'platform'    => 'Mobile',
                'account_age' => '3 years',
                'status'      => 'active',
                'is_featured' => true,
            ],
            [
                'user_id'     => $seller->id,
                'game_id'     => $val->id,
                'title'       => 'Immortal 3 | Full Agent Collection',
                'description' => 'Valorant account with Immortal 3 rank. All agents unlocked. Rare skins included.',
                'price'       => 85.00,
                'rank'        => 'Immortal',
                'level'       => 200,
                'server'      => 'NA',
                'platform'    => 'PC',
                'account_age' => '2 years',
                'status'      => 'active',
                'is_featured' => false,
            ],
            [
                'user_id'     => $seller->id,
                'game_id'     => $pubg->id,
                'title'       => 'Conqueror Season 28 | ACE M762',
                'description' => 'PUBG Mobile Conqueror account. Season 28 border. Multiple rare outfits.',
                'price'       => 65.00,
                'rank'        => 'Conqueror',
                'level'       => 80,
                'server'      => 'Asia',
                'platform'    => 'Mobile',
                'account_age' => '2 years',
                'status'      => 'active',
                'is_featured' => false,
            ],
            [
                'user_id'     => $seller->id,
                'game_id'     => $mlbb->id,
                'title'       => 'Legend Account | 80 Skins | Clean',
                'description' => 'Clean Legend account. 80 skins, all heroes. Perfect for ranked grind.',
                'price'       => 55.00,
                'rank'        => 'Legend',
                'level'       => 90,
                'server'      => 'SEA',
                'platform'    => 'Mobile',
                'account_age' => '1 year',
                'status'      => 'pending',
                'is_featured' => false,
            ],
        ];

        foreach ($listings as $data) {
            Listing::create($data);
        }
    }
}
