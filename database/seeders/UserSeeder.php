<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gametradehub.com'],
            [
                'name'              => 'Admin',
                'username'          => 'admin',
                'password'          => bcrypt('password'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name'              => 'Test User',
                'username'          => 'testuser1',
                'password'          => bcrypt('password'),
                'role'              => 'buyer',
                'wallet_balance'    => 500.00,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user2@test.com'],
            [
                'name'              => 'Test User 2',
                'username'          => 'testuser2',
                'password'          => bcrypt('password'),
                'role'              => 'buyer',
                'wallet_balance'    => 300.00,
                'email_verified_at' => now(),
            ]
        );
    }
}