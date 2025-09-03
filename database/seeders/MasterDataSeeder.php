<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Age Preferences (unchanged)
        DB::table('age_preferences')->insert([
            ['title' => '18-25', 'status' => 'active'],
            ['title' => '26-35', 'status' => 'active'],
            ['title' => '36-45', 'status' => 'active'],
            ['title' => '46-65', 'status' => 'active'],
            ['title' => '65+', 'status' => 'active'],
        ]);

        // Property Types
        DB::table('prefered_property_types')->insert([
            ['title' => 'Single-Family Homes', 'status' => 'active'],
            ['title' => 'Multi-Family Units', 'status' => 'active'],
            ['title' => 'Fix-and-Flip Projects', 'status' => 'active'],
            ['title' => 'Vacation Rentals (Airbnb-style)', 'status' => 'active'],
            ['title' => 'Commercial Spaces', 'status' => 'active'],
            ['title' => 'Land (Raw or Developed)', 'status' => 'active'],
            ['title' => 'Tiny Homes', 'status' => 'active'],
            ['title' => 'Luxury Properties', 'status' => 'active'],
            ['title' => 'Mobile/Modular Homes', 'status' => 'active'],
            ['title' => 'I’m open to all property types', 'status' => 'active'],
        ]);

        // Roles in Real Estate (Choose Your Identities)
        DB::table('choose_your_identities')->insert([
            ['title' => 'New Investor', 'status' => 'active'],
            ['title' => 'Experienced Investor', 'status' => 'active'],
            ['title' => 'Passive Income Seeker', 'status' => 'active'],
            ['title' => 'First-Time Buyer', 'status' => 'active'],
            ['title' => 'Licensed Real Estate Agent', 'status' => 'active'],
            ['title' => 'Real Estate Coach or Mentor', 'status' => 'active'],
            ['title' => 'Wholesaler', 'status' => 'active'],
            ['title' => 'Landlord / Property Manager', 'status' => 'active'],
            ['title' => 'Developer', 'status' => 'active'],
            ['title' => 'Just Exploring', 'status' => 'active'],
        ]);

        // Investment Budget
        DB::table('budgets')->insert([
            ['title' => 'Under $50K', 'status' => 'active'],
            ['title' => '$50K – $150K', 'status' => 'active'],
            ['title' => '$150K – $500K', 'status' => 'active'],
            ['title' => '$500K – $1M', 'status' => 'active'],
            ['title' => '$1M+', 'status' => 'active'],
            ['title' => 'Not sure yet', 'status' => 'active'],
        ]);
    }
}
