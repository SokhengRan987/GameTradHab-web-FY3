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
        // Create seller if not exists
        $seller = User::firstOrCreate(
            ['email' => 'seller@test.com'],
            [
                'name' => 'Demo Seller',
                'password' => bcrypt('password'),
            ]
        );

        // Get games
        $mlbb = Game::where('slug', 'mobile-legends')->first();
        $val  = Game::where('slug', 'valorant')->first();
        $pubg = Game::where('slug', 'pubg-mobile')->first();

        // Stop seeder if games missing
        if (!$mlbb || !$val || !$pubg) {
            return;
        }

        $listings = [
            [
                'game_id'     => $mlbb->id,
                'title'       => 'Mythic Account | 150 Skins | All Heroes',
                'price'       => 120.00,
                'rank'        => 'Mythic',
                'level'       => 120,
                'platform'    => 'Mobile',
                'status'      => 'active',
                'is_featured' => true,
            ],
            [
                'game_id'     => $val->id,
                'title'       => 'Immortal 3 | Full Agent Collection',
                'price'       => 85.00,
                'rank'        => 'Immortal',
                'level'       => 200,
                'platform'    => 'PC',
                'status'      => 'active',
                'is_featured' => false,
            ],
            [
                'game_id'     => $pubg->id,
                'title'       => 'Conqueror Season 28 | ACE M762',
                'price'       => 65.00,
                'rank'        => 'Conqueror',
                'level'       => 80,
                'platform'    => 'Mobile',
                'status'      => 'active',
                'is_featured' => false,
            ],
            [
                'game_id'     => $mlbb->id,
                'title'       => 'Legend Account | 80 Skins | Clean',
                'price'       => 55.00,
                'rank'        => 'Legend',
                'level'       => 90,
                'platform'    => 'Mobile',
                'status'      => 'pending',
                'is_featured' => false,
            ],
        ];

        foreach ($listings as $data) {
            Listing::firstOrCreate(
                ['title' => $data['title']],
                array_merge($data, [
                    'user_id' => $seller->id,
                ])
            );
        }
    }
}