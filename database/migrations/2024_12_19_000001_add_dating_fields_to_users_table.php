<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Basic dating profile fields
            $table->date('date_of_birth')->nullable();
            $table->string('location')->nullable();
            $table->enum('relationship_goal', ['casual', 'serious', 'friendship', 'marriage'])->nullable();
            $table->integer('preferred_age_min')->nullable();
            $table->integer('preferred_age_max')->nullable();
            
            // Real estate preferences
            $table->enum('preferred_property_type', ['apartment', 'house', 'condo', 'townhouse', 'studio', 'any'])->nullable();
            $table->enum('identity', ['buyer', 'seller', 'renter', 'investor'])->nullable();
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->string('preferred_location')->nullable();
            
            // Personal questions
            $table->text('perfect_weekend')->nullable();
            $table->text('cant_live_without')->nullable();
            $table->text('quirky_fact')->nullable();
            $table->text('about_me')->nullable();
            
            // Tags (stored as JSON)
            $table->json('tags')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'location',
                'relationship_goal',
                'preferred_age_min',
                'preferred_age_max',
                'preferred_property_type',
                'identity',
                'budget_min',
                'budget_max',
                'preferred_location',
                'perfect_weekend',
                'cant_live_without',
                'quirky_fact',
                'about_me',
                'tags'
            ]);
        });
    }
}; 