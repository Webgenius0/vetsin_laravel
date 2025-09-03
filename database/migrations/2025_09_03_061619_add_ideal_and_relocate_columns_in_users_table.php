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
            // legacy already exists: relationship_goal
            $table->unsignedBigInteger('ideal_connection')->nullable();
            $table->unsignedBigInteger('willing_to_relocate')->nullable();

            // Add foreign keys (optional but recommended)
            $table->foreign('ideal_connection')->references('id')->on('ideal_connections')->onDelete('set null');
            $table->foreign('willing_to_relocate')->references('id')->on('willing_to_relocates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ideal_connection', 'willing_to_relocate']);
        });
    }
};
