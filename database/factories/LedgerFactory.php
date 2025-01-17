<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ledger>
 */
final class LedgerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = \App\Models\User::factory()->create();

        return [
            'user_id' => $user->id,
            'sender_id' => $user->id,
            'receiver_id' => $user->id,
            'transact_by_id' => $user->id,
            'type' => 'wallet',
            'transaction_date' => date('Y-m-d'),
            'description' => 'Transaction',
            'debit' => fake()->randomNumber(),
            'credit' => fake()->randomNumber(),
            'balance' => fake()->randomNumber(),
            'status' => 'completed',
        ];
    }
}
