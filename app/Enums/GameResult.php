<?php

declare(strict_types=1);

namespace App\Enums;

use Exception;

enum GameResult: string
{
    case MERON = 'meron';
    case WALA = 'wala';
    case DRAW = 'draw';
    case CANCELLED = 'cancelled';
    case PENDING = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::MERON => 'Meron',
            self::WALA => 'Wala',
            self::DRAW => 'Draw',
            self::CANCELLED => 'Cancelled',
            self::PENDING => 'Pending',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MERON => 'red',
            self::WALA => 'blue',
            self::DRAW => 'green',
            self::CANCELLED => 'gray',
            self::PENDING => 'stone',
        };
    }

    /**
     * @throws Exception
     */
    public function side(): BetSide
    {
        return match ($this) {
            self::MERON => BetSide::Meron,
            self::WALA => BetSide::Wala,
            self::DRAW => BetSide::Draw,
            default => throw new Exception('Invalid result'),
        };
    }
}
