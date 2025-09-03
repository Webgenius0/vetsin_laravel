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
        Schema::create('fun_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('type', ['fun1', 'fun2', 'fun3']); // category
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fun_prompts');
    }
};
