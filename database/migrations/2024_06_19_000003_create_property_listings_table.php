<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('property_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->decimal('current_value', 15, 2)->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->enum('property_type', ['apartment', 'house', 'condo', 'townhouse', 'studio', 'land', 'other'])->nullable();
            $table->enum('ownership_type', ['owner', 'agent', 'developer', 'other'])->nullable();
            $table->json('images')->nullable();
            $table->string('external_link')->nullable();
            $table->json('property_tags')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_listings');
    }
}; 