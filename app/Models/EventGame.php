<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GameResult;
use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class EventGame extends Model
{
    use HasFactory;

    public $casts = [
        'result' => GameResult::class,
        'status' => GameStatus::class,
    ];
}
