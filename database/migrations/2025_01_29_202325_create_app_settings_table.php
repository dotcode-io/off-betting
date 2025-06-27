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
        Schema::create('app_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('app_name')->default('OCBS');
            $table->string('app_logo_url')->nullable();
            $table->decimal('draw_win_multiplier', 5, 2)->default(8);
            $table->decimal('bet_multiplier', 5, 2)->default(0);
            $table->decimal('plasada', 10, 2)->default(5);
            $table->timestamps();
        });

        DB::table('app_settings')->insert(
            [
                'app_name' => 'OCBS - KCI Gaming Services',
                'plasada' => 6,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
