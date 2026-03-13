<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Game;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $games = [
            [
                'name'           => 'Mobile Legends',
                'slug'           => 'mobile-legends',
                'category'       => 'MOBA',
                'platform'       => 'Mobile',
                'rank_options'   => ['Warrior','Elite','Master','Grandmaster','Epic','Legend','Mythic'],
                'server_options' => ['SEA','NA','EU','SA'],
                'is_active'      => true,
            ],
            [
                'name'           => 'Valorant',
                'slug'           => 'valorant',
                'category'       => 'FPS',
                'platform'       => 'PC',
                'rank_options'   => ['Iron','Bronze','Silver','Gold','Platinum','Diamond','Immortal','Radiant'],
                'server_options' => ['NA','EU','AP','KR','BR'],
                'is_active'      => true,
            ],
            [
                'name'           => 'PUBG Mobile',
                'slug'           => 'pubg-mobile',
                'category'       => 'Battle Royale',
                'platform'       => 'Mobile',
                'rank_options'   => ['Bronze','Silver','Gold','Platinum','Diamond','Crown','Ace','Conqueror'],
                'server_options' => ['Asia','Europe','NA','KRJP','SEA'],
                'is_active'      => true,
            ],
            [
                'name'           => 'Genshin Impact',
                'slug'           => 'genshin-impact',
                'category'       => 'RPG',
                'platform'       => 'All',
                'rank_options'   => ['AR1-10','AR11-20','AR21-30','AR31-40','AR41-50','AR55-60'],
                'server_options' => ['Asia','America','Europe','TW/HK/MO'],
                'is_active'      => true,
            ],
            [
                'name'           => 'Free Fire',
                'slug'           => 'free-fire',
                'category'       => 'Battle Royale',
                'platform'       => 'Mobile',
                'rank_options'   => ['Bronze','Silver','Gold','Platinum','Diamond','Heroic','Grandmaster'],
                'server_options' => ['SEA','SA','NA','MENA','IND'],
                'is_active'      => true,
            ],
        ];
        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
