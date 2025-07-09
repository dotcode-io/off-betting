<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->index(['bet_at','status']);
            $table->index(['claimed_at','is_claimed']);
            $table->index(['event_id', 'created_at']);
            $table->index(['event_id']);


        });
    }

    public function down(): void
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->dropIndex(['bet_at','status']);;
            $table->dropIndex(['claimed_at','is_claimed']);
            $table->dropIndex(['event_id', 'created_at']);
            $table->dropIndex(['event_id']);

        });
    }
};
