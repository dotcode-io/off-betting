<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventGame extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'game_number',
        'meron_entry',
        'wala_entry',
        'meron_odds',
        'wala_odds',
        'meron_bettors',
        'wala_bettors',
        'draw_bettors',
        'meron_bets',
        'wala_bets',
        'draw_bets',
        'earnings',
        'draw_earnings',
        'status',
        'result',
        'plasada',
        'opened_at',
        'closed_at',
        'done_at',
    ];
}
