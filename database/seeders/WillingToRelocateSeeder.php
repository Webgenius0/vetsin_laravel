<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WillingToRelocateSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $rows = [
            ['title' => 'Yes, for both', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Yes, for love only', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Yes, for real estate only', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => "No, I’m rooted", 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'For the right deal', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('willing_to_relocates')->insert($rows);
    }
}
