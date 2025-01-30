<?php

declare(strict_types=1);

namespace App\Enums;

enum ResultEnum: string
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
            self::CANCELLED => 'zinc',
            self::PENDING => 'stone',
        };
    }


}
