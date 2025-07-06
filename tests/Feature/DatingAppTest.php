<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\EmailOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class DatingAppTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        // Generate JWT token
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_complete_basic_profile()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post('/api/profile/basic', [
            'date_of_birth' => '1990-05-15',
            'location' => 'New York, NY',
            'relationship_goal' => 'serious',
            'preferred_age_min' => 25,
            'preferred_age_max' => 35,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Basic profile completed successfully',
        ]);

        $this->user->refresh();
        $this->assertEquals('1990-05-15', $this->user->date_of_birth->format('Y-m-d'));
        $this->assertEquals('New York, NY', $this->user->location);
        $this->assertEquals('serious', $this->user->relationship_goal);
        $this->assertEquals(25, $this->user->preferred_age_min);
        $this->assertEquals(35, $this->user->preferred_age_max);
        $this->assertEquals(33, $this->user->age);
    }

    public function test_complete_real_estate_preferences()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post('/api/profile/real-estate', [
            'preferred_property_type' => 'apartment',
            'identity' => 'buyer',
            'budget_min' => 200000,
            'budget_max' => 500000,
            'preferred_location' => 'Manhattan, NY',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Real estate preferences completed successfully',
        ]);

        $this->user->refresh();
        $this->assertEquals('apartment', $this->user->preferred_property_type);
        $this->assertEquals('buyer', $this->user->identity);
        $this->assertEquals(200000, $this->user->budget_min);
        $this->assertEquals(500000, $this->user->budget_max);
        $this->assertEquals('Manhattan, NY', $this->user->preferred_location);
    }

    public function test_complete_personal_questions()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post('/api/profile/personal-questions', [
            'perfect_weekend' => 'Exploring new restaurants and hiking in the mountains',
            'cant_live_without' => 'A cozy reading nook with natural light',
            'quirky_fact' => 'I can recite the entire alphabet backwards',
            'about_me' => 'I\'m a passionate foodie who loves to travel and explore new cultures.',
            'tags' => ['foodie', 'traveler', 'adventurous'],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Personal questions completed successfully',
        ]);

        $this->user->refresh();
        $this->assertEquals('Exploring new restaurants and hiking in the mountains', $this->user->perfect_weekend);
        $this->assertEquals('A cozy reading nook with natural light', $this->user->cant_live_without);
        $this->assertEquals('I can recite the entire alphabet backwards', $this->user->quirky_fact);
        $this->assertEquals('I\'m a passionate foodie who loves to travel and explore new cultures.', $this->user->about_me);
        $this->assertEquals(['foodie', 'traveler', 'adventurous'], $this->user->tags);
    }

    public function test_get_profile_status()
    {
        // First complete the profile
        $this->user->update([
            'date_of_birth' => '1990-05-15',
            'location' => 'New York, NY',
            'relationship_goal' => 'serious',
            'preferred_age_min' => 25,
            'preferred_age_max' => 35,
            'preferred_property_type' => 'apartment',
            'identity' => 'buyer',
            'budget_min' => 200000,
            'budget_max' => 500000,
            'preferred_location' => 'Manhattan, NY',
            'perfect_weekend' => 'Exploring new restaurants',
            'cant_live_without' => 'A cozy reading nook',
            'quirky_fact' => 'I can recite the alphabet backwards',
            'about_me' => 'I\'m a passionate foodie',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->get('/api/profile/status');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'basic_profile_complete' => true,
                'real_estate_complete' => true,
                'personal_questions_complete' => true,
                'overall_complete' => true,
            ],
        ]);
    }

    public function test_update_tags()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post('/api/profile/tags', [
            'tags' => ['foodie', 'traveler', 'adventurous', 'bookworm'],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Tags updated successfully',
        ]);

        $this->user->refresh();
        $this->assertEquals(['foodie', 'traveler', 'adventurous', 'bookworm'], $this->user->tags);
    }

    public function test_validation_errors()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post('/api/profile/basic', [
            'date_of_birth' => 'invalid-date',
            'preferred_age_max' => 15, // Less than min age
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => false,
            'message' => 'Validation Error',
        ]);
    }

    public function test_user_data_includes_new_fields()
    {
        // Set up user with dating app data
        $this->user->update([
            'date_of_birth' => '1990-05-15',
            'location' => 'New York, NY',
            'relationship_goal' => 'serious',
            'preferred_age_min' => 25,
            'preferred_age_max' => 35,
            'tags' => ['foodie', 'traveler'],
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->get('/api/users/data');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'date_of_birth' => '1990-05-15',
                'location' => 'New York, NY',
                'relationship_goal' => 'serious',
                'preferred_age_min' => 25,
                'preferred_age_max' => 35,
                'age' => 33,
                'is_profile_complete' => true,
                'tags' => ['foodie', 'traveler'],
            ],
        ]);
    }
} 