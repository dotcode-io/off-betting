<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Bet extends Model
{
    use HasUuids;

    protected $fillable = [
        'uuid',
        'event_id',
        'user_id',
        'nickname',
        'bet_amount',
        'win_amount',
        'side',
        'status',
        'result',
        'is_claimed',
        'bet_at',
        'claimed_by',
        'claimed_at',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
