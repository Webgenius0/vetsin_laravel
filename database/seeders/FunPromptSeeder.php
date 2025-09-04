<?php

namespace Database\Seeders;

use App\Models\FunPrompt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FunPromptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table first
        \App\Models\FunPrompt::truncate();

        $prompts = [
            // Prompt 1
            ['title' => 'Touring properties or open houses', 'status' => 'active', 'type' => 'fun1'],
            ['title' => 'A cozy morning with coffee and a spreadsheet', 'status' => 'active', 'type' => 'fun1'],
            ['title' => 'Exploring a new city', 'status' => 'active', 'type' => 'fun1'],
            ['title' => 'Brunch and a good podcast', 'status' => 'active', 'type' => 'fun1'],
            ['title' => 'Catching up on my latest project', 'status' => 'active', 'type' => 'fun1'],
            ['title' => 'Off-grid and unplugged', 'status' => 'active', 'type' => 'fun1'],

            // Prompt 2
            ['title' => 'A sun-drenched workspace', 'status' => 'active', 'type' => 'fun2'],
            ['title' => 'Peace and privacy', 'status' => 'active', 'type' => 'fun2'],
            ['title' => 'Room to grow or host', 'status' => 'active', 'type' => 'fun2'],
            ['title' => 'Smart home features', 'status' => 'active', 'type' => 'fun2'],
            ['title' => 'Investment potential', 'status' => 'active', 'type' => 'fun2'],
            ['title' => 'A unique design feature', 'status' => 'active', 'type' => 'fun2'],

            // Prompt 3
            ['title' => 'I research cities for fun', 'status' => 'active', 'type' => 'fun3'],
            ['title' => 'I can walk a property and imagine its ROI', 'status' => 'active', 'type' => 'fun3'],
            ['title' => 'I travel light but dream big', 'status' => 'active', 'type' => 'fun3'],
            ['title' => 'I name all my properties', 'status' => 'active', 'type' => 'fun3'],
            ['title' => 'I secretly love watching house-flipping fails', 'status' => 'active', 'type' => 'fun3'],
            ['title' => 'I track Zillow for sport', 'status' => 'active', 'type' => 'fun3'],
        ];

        \App\Models\FunPrompt::insert($prompts);
    }
}
