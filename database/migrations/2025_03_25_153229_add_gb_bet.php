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
        Schema::table('event_games', function (Blueprint $table) {

            $table->decimal('gb_bets', 15, 2)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_games', function (Blueprint $table) {
            $table->dropColumn('gb_bets');
        });
    }
};
