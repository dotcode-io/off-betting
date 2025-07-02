<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->double('current_month_gb_earning');
            $table->double('current_month_earning');
            $table->double('daily_month_gb_earning');
            $table->double('daily_month_earning');
            $table->double('daily_total_bet');
            $table->double('daily_total_withdrawal');
            $table->foreignId('event_id')->constrained();
            $table->timestamps();
            $table->unique(['event_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
