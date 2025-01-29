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
        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('arena');
            $table->date('date');
            $table->integer('start_of_game')->default(1);
            $table->integer('number_of_games');
            $table->unsignedBigInteger('total_meron_wins')->default(0);
            $table->unsignedBigInteger('total_wala_wins')->default(0);
            $table->unsignedBigInteger('total_draws')->default(0);
            $table->unsignedBigInteger('total_cancelled')->default(0);
            $table->decimal('total_meron_bets', 16, 2)->default(0);
            $table->decimal('total_wala_bets', 16, 2)->default(0);
            $table->decimal('total_draw_bets', 16, 2)->default(0);
            $table->decimal('total_bets', 16, 2)->default(0);
            $table->decimal('total_earnings', 16, 2)->default(0);
            $table->decimal('total_draw_earnings', 16, 2)->default(0);
            $table->decimal('plasada', 10, 2)->default(0);
            $table->enum('status', ['pending', 'open', 'close', 'done'])->default('pending');
            $table->dateTime('opened_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->unsignedBigInteger('opened_by')->nullable();
            $table->foreign('opened_by')->references('id')->on('users');
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->foreign('closed_by')->references('id')->on('users');
            $table->unsignedBigInteger('done_by')->nullable();
            $table->foreign('done_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
