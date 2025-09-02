<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [];

        // Willing to relocate options
        $rows = array_merge($rows, [
            ['group' => 'willing_to_relocate', 'key' => 'both',               'label' => 'Yes, for both',               'info' => null, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'willing_to_relocate', 'key' => 'love_only',         'label' => 'Yes, for love only',         'info' => null, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'willing_to_relocate', 'key' => 'real_estate_only',  'label' => 'Yes, for real estate only', 'info' => null, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'willing_to_relocate', 'key' => 'no_rooted',         'label' => "No, I’m rooted",             'info' => null, 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'willing_to_relocate', 'key' => 'right_deal',        'label' => 'For the right deal',         'info' => null, 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Ideal connection options (with info for ℹ️)
        $rows = array_merge($rows, [
            ['group' => 'ideal_connection', 'key' => 'casual_intentional',       'label' => 'Casual but Intentional',    'info' => 'Open to dating casually with clear boundaries and mutual respect.', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'ideal_connection', 'key' => 'committed_partnership',   'label' => 'Committed Partnership',     'info' => 'Looking for something exclusive, grounded in trust and mutual growth.', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'ideal_connection', 'key' => 'marriage_minded',         'label' => 'Marriage-Minded',          'info' => 'Ready to build a lifelong partnership and legacy.', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'ideal_connection', 'key' => 'friendship_first',        'label' => 'Friendship First',         'info' => 'Genuine connections that may evolve into something deeper.', 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'ideal_connection', 'key' => 'open_to_unfolds',         'label' => 'Open to What Unfolds',     'info' => 'Letting chemistry lead while staying open-minded.', 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'ideal_connection', 'key' => 'intentional_coinvesting', 'label' => 'Intentional Co-Investing', 'info' => 'Looking for love and a business-minded partner in real estate.', 'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'ideal_connection', 'key' => 'international_connections', 'label' => 'International Connections', 'info' => 'Open to global love and investment adventures.', 'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'ideal_connection', 'key' => 'conscious_coupling_fahim', 'label' => 'Conscious Coupling', 'info' => 'Seeking a mindful connection rooted in purpose and depth.', 'sort_order' => 8, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Age preference brackets (include 65+)
        $rows = array_merge($rows, [
            ['group' => 'age_preferences', 'key' => '18_24',   'label' => '18–24', 'info' => null, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'age_preferences', 'key' => '25_34',   'label' => '25–34', 'info' => null, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'age_preferences', 'key' => '35_44',   'label' => '35–44', 'info' => null, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'age_preferences', 'key' => '45_54',   'label' => '45–54', 'info' => null, 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'age_preferences', 'key' => '55_64',   'label' => '55–64', 'info' => null, 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['group' => 'age_preferences', 'key' => '65_plus', 'label' => '65+',   'info' => null, 'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('profile_options')->insert($rows);
    }
}
