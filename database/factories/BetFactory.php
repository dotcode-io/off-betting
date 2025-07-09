<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\GameResult;
use App\Models\Bet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bet>
 */
final class BetFactory extends Factory
{
    protected $model = Bet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $betSides = [BetSide::Meron, BetSide::Wala, BetSide::Draw];
        $betSide = $this->faker->randomElement($betSides);
        $betAmount = $this->faker->numberBetween(10, 1000);

        return [
            'uuid' => Str::uuid(),
            'reference_no' => 'BET' . $this->faker->unique()->numerify('########'),
            'event_id' => 1,
            'event_game_id' => 1,
            'user_id' => $this->faker->numberBetween(3, 1000),
            'nickname' => $this->faker->userName,
            'bet_amount' => $betAmount,
            'win_amount' => 0,
            'side' => $betSide->value,
            'status' => BetStatus::OnGoing->value,
            'result' => GameResult::PENDING->value,
            'is_claimed' => false,
            'bet_at' => $this->faker->dateTime,
            'claimed_at' => null,
        ];
    }

    /**
     * Create a ghostbet (user_id = 2)
     */
    public function ghostbet(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => 2,
            'nickname' => 'GhostBot',
            'reference_no' => 'GB' . $this->faker->unique()->numerify('########'),
        ]);
    }

    /**
     * Set the bet as winner
     */
    public function winner(float $winAmount): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BetStatus::Winner->value,
            'win_amount' => $winAmount,
            'is_claimed' => $this->faker->boolean(70), // 70% chance of being claimed
            'claimed_at' => $this->faker->boolean(70) ? $this->faker->dateTime : null,
        ]);
    }

    /**
     * Set the bet as loser
     */
    public function loser(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BetStatus::Loser->value,
            'win_amount' => 0,
            'is_claimed' => false,
            'claimed_at' => null,
        ]);
    }
}
