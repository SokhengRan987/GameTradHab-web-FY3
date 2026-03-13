<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin account
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gametradehub.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        //Seller account
        User::create([
            'name' => 'Test Seller',
            'username' => 'seller1',
            'email' => 'seller@test.com',
            'password' => bcrypt('password'),
            'role' => 'seller',
            'email_verified_at' => now(),
        ]);

        //Buyer account (pre-loaded  with $500 for testing)
        User::create([
            'name' => 'Test Buyer',
            'username' => 'buyer1',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password'),
            'role' => 'buyer',
            'wallet_balance' => 500.00,
            'email_verified_at' => now(),
        ]);
    }
}
