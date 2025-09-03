<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IdealConnectionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $rows = [
            ['title' => 'Casual but Intentional', 'info' => 'Open to dating casually with clear boundaries and mutual respect.', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Committed Partnership', 'info' => 'Looking for something exclusive, grounded in trust and mutual growth.', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Marriage-Minded', 'info' => 'Ready to build a lifelong partnership and legacy.', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Friendship First', 'info' => 'Genuine connections that may evolve into something deeper.', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Open to What Unfolds', 'info' => 'Letting chemistry lead while staying open-minded.', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Intentional Co-Investing', 'info' => 'Looking for love and a business-minded partner in real estate.', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'International Connections', 'info' => 'Open to global love and investment adventures.', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Conscious Coupling', 'info' => 'Seeking a mindful connection rooted in purpose and depth.', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('ideal_connections')->insert($rows);
    }
}
