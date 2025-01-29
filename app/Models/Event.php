<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName()
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
}
