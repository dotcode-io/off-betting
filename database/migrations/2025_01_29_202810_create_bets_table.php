<?php

declare(strict_types=1);

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
        Schema::create('bets', function (Blueprint $table): void {
            $table->id();
            $table->string('uuid')->unique();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('event_game_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nickname')->nullable();
            $table->decimal('bet_amount', 16, 2)->default(0);
            $table->decimal('win_amount', 16, 2)->default(0);
            $table->string('side')->nullable();
            $table->string('status')->default('on-going');
            $table->string('result')->nullable();
            $table->tinyInteger('is_claimed')->default(0);
            $table->dateTime('bet_at');
            $table->unsignedBigInteger('claimed_by')->nullable();
            $table->dateTime('claimed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
