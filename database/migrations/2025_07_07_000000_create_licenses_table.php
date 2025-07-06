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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->integer('month'); // 1-12
            $table->integer('year'); // e.g., 2025
            $table->string('license_key')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at');
            $table->timestamps();

            // Ensure only one active license per month/year
            $table->unique(['month', 'year', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
