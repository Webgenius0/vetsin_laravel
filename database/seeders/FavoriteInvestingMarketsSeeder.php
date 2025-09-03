<?php

namespace Database\Seeders;

use App\Models\FavoriteInvestingMarket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteInvestingMarketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markets = [
            'Detroit, MI',
            'Atlanta, GA',
            'Florida',
            'Dallas, TX',
            'Los Angeles, CA',
            'Arizona',
            'Open to many markets',
        ];

        foreach ($markets as $market) {
            FavoriteInvestingMarket::create([
                'title'  => $market,
                'status' => 'active',
            ]);
        }
    }
}
