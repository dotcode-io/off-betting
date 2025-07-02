<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\GameStatus;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Override;

final class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, HasUuids;

    public $casts = [
        'date' => 'date',
        'status' => EventStatus::class,
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public static function getUuidInCache(int $id): string
    {
        return (string) Cache::remember("event_uuid_{$id}", (60 * 60 * 24), fn () => Event::query()
            ->select('uuid')
            ->find($id)->uuid);
    }

    /**
     * @return self
     */

    public static function getCurrent(): self
    {
        return self::query()
            ->where('status', EventStatus::OPENED)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function dateFormatted(): string
    {
        return $this->date->format('F d, Y');
    }

    public function openedAtFormatted(): string
    {
        return $this->opened_at?->format('F d, Y H:i A') ?? 'Not opened yet';
    }

    public function closedAtFormatted(): string
    {
        return $this->closed_at?->format('F d, Y H:i A') ?? 'Not closed yet';
    }

    public function games(): HasMany
    {
        return $this->hasMany(EventGame::class, 'event_id', 'id');
    }

    public function createGames(): void
    {
        $settings = Cache::remember('app_settings', 600, fn () => AppSetting::first());

        $end = $this->number_of_games;
        if ($end % 6 === 0) {
            $result = $end + 12;
        } else {
            $nearestMultiple = ceil($end / 6) * 6;
            $difference = $nearestMultiple - $end;
            $extraValue = $this->start_of_game - 1;
            $result = $end + $difference + 24 + $extraValue;
        }
        $games = [];
        for ($i = $this->start_of_game; $i <= $result; $i++) {
            $games[] = [
                'event_id' => $this->id,
                'game_number' => $i,
                'status' => 'pending',
                'result' => 'pending',
                'plasada' => $settings->plasada ?? 5,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        EventGame::insert($games);

    }

    /**
     *
     * @throws Exception
     */
    public function getCurrentGame(): EventGame
    {
        if ($this->status !== EventStatus::OPENED) {
            throw new Exception('Event is not opened');
        }

        return $this->games()->whereIn('status', [
            GameStatus::PENDING,
            GameStatus::OPENED,
            GameStatus::CLOSED,
        ])->orderBy('game_number', 'asc')->first();
    }

    public function getOpenGame(): EventGame
    {
        if ($this->status !== EventStatus::OPENED) {
            throw new Exception('Event is not opened');
        }

        return $this->games()->where('status',
            GameStatus::OPENED
        )->orderBy('game_number', 'asc')->firstOrFail();
    }
}
