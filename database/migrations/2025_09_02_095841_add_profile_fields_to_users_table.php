<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // legacy already exists: relationship_goal
            $table->string('ideal_connection')->nullable()->after('relationship_goal');
            $table->string('willing_to_relocate')->nullable()->after('ideal_connection');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ideal_connection', 'willing_to_relocate']);
        });
    }
};
