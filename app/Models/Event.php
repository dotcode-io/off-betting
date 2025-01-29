<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<int, string>
     */

    protected $fillable = [
        'uuid',
        'name',
        'arena',
        'date',
        'start_of_game',
        'number_of_games',
        'total_meron_wins',
        'total_wala_wins',
        'total_draws',
        'total_cancelled',
        'total_meron_bets',
        'total_wala_bets',
        'total_draw_bets',
        'total_bets',
        'total_earnings',
        'draw_earnings',
        'plasada',
        'status',
        'opened_at',
        'closed_at',
        'done_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'opened_by',
        'closed_by',
        'done_by',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
