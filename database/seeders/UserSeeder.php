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

        // Test User 1 — can both buy and sell
        User::create([
            'name'              => 'Test User',
            'username'          => 'testuser1',
            'email'             => 'user@test.com',
            'password'          => bcrypt('password'),
            'role'              => 'buyer',
            'wallet_balance'    => 500.00,
            'email_verified_at' => now(),
        ]);

        // Test User 2 — can both buy and sell
        User::create([
            'name'              => 'Test User 2',
            'username'          => 'testuser2',
            'email'             => 'user2@test.com',
            'password'          => bcrypt('password'),
            'role'              => 'buyer',
            'wallet_balance'    => 300.00,
            'email_verified_at' => now(),
        ]);
    }
}
