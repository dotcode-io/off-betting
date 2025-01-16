<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ledger>
 */
class LedgerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->text(),
            'user_id' => fake()->text(),
            'sender_id' => fake()->text(),
            'receiver_id' => fake()->text(),
            'transact_by_id' => fake()->text(),
            'type' => fake()->text(),
            'transaction_date' => $this->faker->dateTime(),
            'description' => fake()->text(),
            'debit' => fake()->text(),
            'credit' => fake()->text(),
            'balance' => fake()->text(),
            'status' => fake()->text(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
