<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    private static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->userName(),
            'wallet_amount' => 0,
            'commission_amount' => 0,
            'user_type' => 'player',
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function player(): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_type' => 'player',
        ]);
    }

    public function teller(): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_type' => 'teller',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_type' => 'admin',
        ]);
    }
}
