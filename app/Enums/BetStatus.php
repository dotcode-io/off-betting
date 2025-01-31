<?php

declare(strict_types=1);

namespace App\Enums;

enum BetStatus: string
{
    case OnGoing = 'on-going';
    case Winner = 'winner';
    case Loser = 'loser';
}
