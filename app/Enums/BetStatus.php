<?php

namespace App\Enums;

enum BetStatus: string
{

    case OnGoing = 'on-going';
    case Winner = 'winner';
    case Loser = 'loser';
}
