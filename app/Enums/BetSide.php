<?php

namespace App\Enums;

enum BetSide: string
{

    case Meron = 'meron';
    case Wala = 'wala';
    case Draw = 'draw';

    public function label(): string
    {
        return match ($this) {
            self::Meron => 'Meron',
            self::Wala => 'Wala',
            self::Draw => 'Draw',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Meron => 'green',
            self::Wala => 'red',
            self::Draw => 'blue',
        };
    }
}
