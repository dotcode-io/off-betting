<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ledgers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('sender_id')->constrained('users')->nullable();
            $table->foreignId('receiver_id')->constrained('users')->nullable();
            $table->foreignId('transact_by_id')->constrained('users');
            $table->enum('type', ['wallet', 'commission']);
            $table->timestamp('transaction_date');
            $table->string('description');
            $table->decimal('debit', 10, 2)->nullable();
            $table->decimal('credit', 10, 2)->nullable();
            $table->decimal('balance', 10, 2);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->timestamps();

        });

        if (! app()->environment('testing')) {
            // Add CHECK constraint for 'debit >= 0' on ledgers
            DB::statement('ALTER TABLE ledgers ADD CONSTRAINT chk_ledgers_debit_non_negative CHECK (debit >= 0)');
            // Add CHECK constraint for 'credit >= 0' on ledgers
            DB::statement('ALTER TABLE ledgers ADD CONSTRAINT chk_ledgers_credit_non_negative CHECK (credit >= 0)');
            // Add CHECK constraint for 'balance >= 0' on ledgers
            DB::statement('ALTER TABLE ledgers ADD CONSTRAINT chk_ledgers_balance_non_negative CHECK (balance >= 0)');
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
