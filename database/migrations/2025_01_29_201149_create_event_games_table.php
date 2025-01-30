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
        Schema::create('event_games', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedInteger('game_number');
            $table->string('meron_entry')->nullable();
            $table->string('wala_entry')->nullable();
            $table->decimal('meron_odds', 16, 2)->default(0);
            $table->decimal('wala_odds', 16, 2)->default(0);
            $table->unsignedInteger('meron_bettors')->default(0);
            $table->unsignedInteger('wala_bettors')->default(0);
            $table->unsignedInteger('draw_bettors')->default(0);
            $table->decimal('meron_bets', 16, 2)->default(0);
            $table->decimal('wala_bets', 16, 2)->default(0);
            $table->decimal('draw_bets', 16, 2)->default(0);
            $table->decimal('earnings', 16, 2)->default(0);
            $table->decimal('draw_earnings', 16, 2)->default(0);
            $table->enum('status', ['pending', 'open', 'close', 'done'])->default('pending');
            $table->enum('result', ['meron', 'wala', 'draw', 'cancelled', 'pending'])->nullable();
            $table->decimal('plasada', 16, 2)->default(0);
            $table->dateTime('opened_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_games');
    }
};
