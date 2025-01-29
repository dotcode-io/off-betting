<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    public $casts = [
        'date' => 'date',
        'status' => EventStatus::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<int, string>
     */
    public function dateFormated(): string
    {
        return $this->date->format('F j, Y');
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
