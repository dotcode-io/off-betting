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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('OCBS');
            $table->string('app_logo_url')->nullable();
            $table->decimal('draw_win_multiplier', 5, 2)->default(8);
            $table->decimal('bet_multiplier', 5, 2)->default(0);
            $table->decimal('plasada', 10, 2)->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
