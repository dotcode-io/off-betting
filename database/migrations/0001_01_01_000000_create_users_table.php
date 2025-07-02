<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->double('wallet_amount', 10, 2)->default(0.00);
            $table->double('commission_amount', 10, 2)->default(0.00);
            $table->enum('user_type', ['admin', 'teller', 'controller', 'player'])->default('player');
            $table->bigInteger('version')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        $tellers = [];
        $tellers[] = [
            'uuid' => Illuminate\Support\Str::uuid(),
            'username' => 'ghostbet',
            'user_type' => 'teller',
            'password' => Hash::make('password'),
        ];
        for ($i = 0; $i < 20; $i++) {
            $tellers[] = [
                'uuid' => Illuminate\Support\Str::uuid(),
                'username' => 'teller'.$i + 1,
                'user_type' => 'teller',
                'password' => Hash::make('password'),
            ];
        }
        $users = [
            [
                'uuid' => Illuminate\Support\Str::uuid(),
                'username' => 'admin',
                'user_type' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'uuid' => Illuminate\Support\Str::uuid(),
                'username' => 'controller',
                'user_type' => 'controller',
                'password' => Hash::make('password'),

            ],
        ];
        DB::table('users')->insert(values: array_merge($users, $tellers));

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        if (! app()->environment('testing')) {
            // Add CHECK constraint for 'balance >= 0' on wallets
            DB::statement('ALTER TABLE users ADD CONSTRAINT chk_wallets_balance_non_negative CHECK (wallet_amount >= 0)');

            // Add CHECK constraint for 'balance >= 0' on commissions
            DB::statement('ALTER TABLE users ADD CONSTRAINT chk_commissions_balance_non_negative CHECK (commission_amount >= 0)');
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
