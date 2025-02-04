<?php

declare(strict_types=1);

namespace App\Enums;

enum BetStatus: string
{
    case OnGoing = 'on-going';
    case Winner = 'winner';
    case Loser = 'loser';

    case Refund = 'refund';

    public function label(): string
    {
        return match ($this) {
            self::OnGoing => 'On-Going',
            self::Winner => 'Winner',
            self::Loser => 'Loser',
            self::Refund => 'Refund',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OnGoing => 'orange',
            self::Winner => 'green',
            self::Loser => 'red',
            self::Refund => 'yellow',
        };
    }
}
