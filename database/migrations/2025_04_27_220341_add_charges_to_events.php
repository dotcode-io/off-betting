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
            $table->double('meron_charge',16,2)->default(0);
            $table->double('wala_charge',16,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_games', function (Blueprint $table) {
            $table->dropColumn([
                'meron_charge',
                'wala_charge'
            ]);
        });
    }
};
