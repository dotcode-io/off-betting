<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\GameStatus;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
     * Get the attributes that should be cast.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function dateFormated(): string
    {
        return $this->date->format('F d, Y H:i A');
    }

    public function openedAtFormated(): string
    {
        return $this->opened_at?->format('F d, Y H:i A') ?? 'Not opened yet';
    }

    public function closedAtFormated(): string
    {
        return $this->closed_at?->format('F d, Y H:i A') ?? 'Not closed yet';
    }

    public function games()
    {
        return $this->hasMany(EventGame::class, 'event_id', 'id');
    }

    public function createGames(): void
    {
        if ($this->status !== EventStatus::OPENED) {
            throw new Exception('Event is not opened');
        }
        $games = [];
        for ($i = 0; $i < $this->number_of_games; $i++) {
            $games[] = [
                'event_id' => $this->id,
                'game_number' => $this->start_of_game + $i,
                'status' => 'pending',
                'result' => 'pending',
                'plasada' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        EventGame::insert($games);

    }

    /**
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
