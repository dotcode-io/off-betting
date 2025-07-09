<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\GameResult;
use App\Enums\GameStatus;
use App\Models\EventGame;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventGame>
 */
final class EventGameFactory extends Factory
{
    protected $model = EventGame::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $results = [GameResult::MERON, GameResult::WALA, GameResult::DRAW];
        $result = $this->faker->randomElement($results);

        return [
            'event_id' => 1,
            'game_number' => $this->faker->numberBetween(1, 1000),
            'meron_entry' => 'Meron ' . $this->faker->numberBetween(1, 1000),
            'wala_entry' => 'Wala ' . $this->faker->numberBetween(1, 1000),
            'meron_odds' => $this->faker->randomFloat(2, 1.50, 3.00),
            'wala_odds' => $this->faker->randomFloat(2, 1.50, 3.00),
            'meron_bettors' => 0,
            'wala_bettors' => 0,
            'draw_bettors' => 0,
            'meron_bets' => 0,
            'wala_bets' => 0,
            'draw_bets' => 0,
            'earnings' => 0,
            'draw_earnings' => 0,
            'gb_earnings' => 0,
            'status' => GameStatus::DONE,
            'result' => $result,
            'plasada' => $this->faker->numberBetween(100, 500),
            'opened_at' => $this->faker->dateTime,
            'closed_at' => $this->faker->dateTime,
            'done_at' => $this->faker->dateTime,
        ];
    }
}
