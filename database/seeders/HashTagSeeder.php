<?php

namespace Database\Seeders;

use App\Models\HashTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HashTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            '#Visionary',
            '#DealHunter',
            '#LuxuryLover',
            '#QuietPower',
            '#NomadicInvestor',
            '#GoalGetter',
            '#RenovationJunkie',
            '#CashflowQueen',
            '#EquityBuilder',
            '#ModernMinimalist'
        ];

        foreach ($tags as $tag) {
            HashTag::create(['name' => $tag]);
        }
    }
}
