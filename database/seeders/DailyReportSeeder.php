<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\EventStatus;
use App\Enums\GameResult;
use App\Enums\GameStatus;
use App\Models\Bet;
use App\Models\Event;
use App\Models\EventGame;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Daily Report Seeder for last month...');

        // Get last month's date range
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $this->command->info("Seeding data from {$startOfLastMonth->format('Y-m-d')} to {$endOfLastMonth->format('Y-m-d')}");

        // Full month seeding
        $totalDays = $startOfLastMonth->diffInDays($endOfLastMonth) + 1;

        // Create or find an event for the seeding
        $event = Event::firstOrCreate(
            ['date' => $startOfLastMonth->toDateString()],
            [
                'uuid' => Str::uuid(),
                'name' => 'Daily Report Seeded Event - ' . $startOfLastMonth->format('F Y'),
                'arena' => 'Seeded Arena',
                'date' => $startOfLastMonth->toDateString(),
                'status' => EventStatus::DONE,
                'number_of_games' => 10 * $totalDays,
                'start_of_game' => 1,
                'plasada' => 5.00,
                'opened_at' => $startOfLastMonth,
                'closed_at' => $endOfLastMonth,
                'done_at' => $endOfLastMonth,
                'created_by' => 1, // Assuming admin user ID 1 exists
                'opened_by' => 1,
                'closed_by' => 1,
                'done_by' => 1,
                'created_at' => $startOfLastMonth,
                'updated_at' => $endOfLastMonth,
            ]
        );

        $eventId = $event->id;
        $currentDate = $startOfLastMonth->copy();

        // Process each day with batch insertion
        for ($day = 0; $day < $totalDays; $day++) {
            $dayNumber = $day + 1;
            $this->command->info("Processing day {$dayNumber}/{$totalDays}: {$currentDate->format('Y-m-d')}");

            $this->seedDayData($eventId, $currentDate, $dayNumber);
            $currentDate->addDay();
        }

        $this->command->info('Daily Report Seeder completed successfully!');
    }

    private function seedDayData(int $eventId, Carbon $currentDate, int $dayNumber): void
    {
        // Create 300 EventGames per day using batch insertion
        $eventGames = [];
        $gameStartNumber = ($dayNumber - 1) * 300 + 1;

        for ($gameNumber = $gameStartNumber; $gameNumber < $gameStartNumber + 300; $gameNumber++) {
            $gameTime = $currentDate->copy()->addMinutes(rand(0, 1439));
            $results = [GameResult::MERON, GameResult::WALA, GameResult::DRAW];
            $result = $results[array_rand($results)];
            $meronOdds = rand(150, 300) / 100;
            $walaOdds = rand(150, 300) / 100;

            $eventGames[] = [
                'event_id' => $eventId,
                'game_number' => $gameNumber,
                'meron_entry' => 'Meron ' . $gameNumber,
                'wala_entry' => 'Wala ' . $gameNumber,
                'meron_odds' => $meronOdds,
                'wala_odds' => $walaOdds,
                'meron_bettors' => 0,
                'wala_bettors' => 0,
                'draw_bettors' => 0,
                'meron_bets' => 0,
                'wala_bets' => 0,
                'draw_bets' => 0,
                'earnings' => 0,
                'draw_earnings' => 0,
                'gb_earnings' => 0,
                'status' => GameStatus::DONE->value,
                'result' => $result->value,
                'plasada' => rand(100, 500),
                'opened_at' => $gameTime,
                'closed_at' => $gameTime->copy()->addMinutes(5),
                'done_at' => $gameTime->copy()->addMinutes(10),
                'created_at' => $gameTime,
                'updated_at' => $gameTime,
            ];
        }

        // Batch insert EventGames
        DB::table('event_games')->insert($eventGames);

        // Get the inserted EventGame IDs
        $insertedGames = EventGame::where('event_id', $eventId)
            ->where('game_number', '>=', $gameStartNumber)
            ->where('game_number', '<', $gameStartNumber + 300)
            ->get();

        // Process bets for each game in batches
        foreach ($insertedGames as $eventGame) {
            $this->seedGameBets($eventId, $eventGame, $currentDate);
        }
    }

    private function seedGameBets(int $eventId, EventGame $eventGame, Carbon $currentDate): void
    {
        $gameTime = Carbon::parse($eventGame->opened_at);
        $result = $eventGame->result;
        $meronOdds = $eventGame->meron_odds;
        $walaOdds = $eventGame->wala_odds;

        // Prepare batch data for regular bets (500 per game)
        $regularBets = [];
        $totalBetAmount = 0;
        $totalWinAmount = 0;
        $meronBets = 0;
        $walaBets = 0;
        $drawBets = 0;
        $meronBettors = 0;
        $walaBettors = 0;
        $drawBettors = 0;

        for ($betNumber = 1; $betNumber <= 500; $betNumber++) {
            $userId = rand(3, 1000);
            $betSides = [BetSide::Meron, BetSide::Wala, BetSide::Draw];
            $betSide = $betSides[array_rand($betSides)];
            $betAmount = rand(10, 1000);
            $betTime = $gameTime->copy()->addMinutes(rand(-5, 0));

            // Calculate win amount and status
            $winAmount = 0;
            $betStatus = BetStatus::Loser;

            if (($result === GameResult::MERON && $betSide === BetSide::Meron) ||
                ($result === GameResult::WALA && $betSide === BetSide::Wala) ||
                ($result === GameResult::DRAW && $betSide === BetSide::Draw)) {
                $betStatus = BetStatus::Winner;
                $winAmount = $betSide === BetSide::Draw ? $betAmount * 8 :
                    $betAmount * ($betSide === BetSide::Meron ? $meronOdds : $walaOdds);
            }

            $isClaimed = $betStatus === BetStatus::Winner ? (rand(0, 1) === 1) : false;

            $regularBets[] = [
                'uuid' => Str::uuid(),
                'reference_no' => 'BET' . $currentDate->format('Ymd') . 'G' . $eventGame->id . 'B' . str_pad((string)$betNumber, 4, '0', STR_PAD_LEFT) . rand(100, 999),
                'event_id' => $eventId,
                'event_game_id' => $eventGame->id,
                'user_id' => $userId,
                'nickname' => 'User' . $userId,
                'bet_amount' => $betAmount,
                'win_amount' => $winAmount,
                'side' => $betSide->value,
                'status' => $betStatus->value,
                'result' => $result->value,
                'is_claimed' => $isClaimed,
                'bet_at' => $betTime,
                'claimed_at' => $isClaimed ? $betTime->copy()->addMinutes(rand(10, 60)) : null,
                'created_at' => $betTime,
                'updated_at' => $betTime,
            ];

            // Track totals
            $totalBetAmount += $betAmount;
            $totalWinAmount += $winAmount;

            if ($betSide === BetSide::Meron) {
                $meronBets += $betAmount;
                $meronBettors++;
            } elseif ($betSide === BetSide::Wala) {
                $walaBets += $betAmount;
                $walaBettors++;
            } else {
                $drawBets += $betAmount;
                $drawBettors++;
            }
        }

        // Create 2 ghostbet records per EventGame
        $ghostbetWinAmount = 0;
        for ($ghostbet = 1; $ghostbet <= 2; $ghostbet++) {
            $betSides = [BetSide::Meron, BetSide::Wala];
            $betSide = $betSides[array_rand($betSides)];
            $betAmount = rand(100, 500);
            $betTime = $gameTime->copy()->addMinutes(rand(-5, 0));

            // Calculate ghostbet win amount
            $winAmount = 0;
            $betStatus = BetStatus::Loser;

            if (($result === GameResult::MERON && $betSide === BetSide::Meron) ||
                ($result === GameResult::WALA && $betSide === BetSide::Wala)) {
                $betStatus = BetStatus::Winner;
                $winAmount = $betAmount * ($betSide === BetSide::Meron ? $meronOdds : $walaOdds);
                $ghostbetWinAmount += $winAmount;
            }

            $regularBets[] = [
                'uuid' => Str::uuid(),
                'reference_no' => 'GB' . $currentDate->format('Ymd') . 'G' . $eventGame->id . 'B' . str_pad((string)(500 + $ghostbet), 4, '0', STR_PAD_LEFT) . rand(100, 999),
                'event_id' => $eventId,
                'event_game_id' => $eventGame->id,
                'user_id' => 2, // Ghostbet user ID
                'nickname' => 'GhostBot',
                'bet_amount' => $betAmount,
                'win_amount' => $winAmount,
                'side' => $betSide->value,
                'status' => $betStatus->value,
                'result' => $result->value,
                'is_claimed' => false,
                'bet_at' => $betTime,
                'claimed_at' => null,
                'created_at' => $betTime,
                'updated_at' => $betTime,
            ];

            // Track ghostbet totals
            $totalBetAmount += $betAmount;

            if ($betSide === BetSide::Meron) {
                $meronBets += $betAmount;
                $meronBettors++;
            } else {
                $walaBets += $betAmount;
                $walaBettors++;
            }
        }

        // Batch insert all bets (regular + ghostbets)
        DB::table('bets')->insert($regularBets);

        // Calculate earnings (ensure no negative earnings except for ghostbet or draw)
        $earnings = max(0, $totalBetAmount - $totalWinAmount); // Ensure non-negative
        $drawEarnings = $result === GameResult::DRAW ? $drawBets * 0.9 : 0; // Can be negative for draw
        $gbEarnings = -$ghostbetWinAmount; // Can be negative for ghostbet

        // Update EventGame with calculated values
        $eventGame->update([
            'meron_bettors' => $meronBettors,
            'wala_bettors' => $walaBettors,
            'draw_bettors' => $drawBettors,
            'meron_bets' => $meronBets,
            'wala_bets' => $walaBets,
            'draw_bets' => $drawBets,
            'earnings' => $earnings,
            'draw_earnings' => $drawEarnings,
            'gb_earnings' => $gbEarnings,
        ]);
    }
}
