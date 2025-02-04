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

        Schema::table('bets', function (Blueprint $table): void {
            $table->enum('status', ['on-going', 'winner', 'loser', 'refund'])->default('on-going')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bets', function (Blueprint $table): void {
            $table->enum('status', ['on-going', 'winner', 'loser'])->default('on-going')->change();
        });
    }
};
