<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'is_premium' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'User',
                'email' => 'user@user.com',
                'is_premium' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Premium',
                'email' => 'premium@premium.com',
                'is_premium' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'NA',
                'email' => 'manx734@gmail.com',
                'is_premium' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'superadmin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
        ]);
    }
}
