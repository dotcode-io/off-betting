<?php

declare(strict_types=1);

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case CONTROLLER = 'controller';
    case TELLER = 'teller';

    case PLAYER = 'player';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::CONTROLLER => 'Controller',
            self::TELLER => 'Teller',
            self::PLAYER => 'Player',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'yellow',
            self::CONTROLLER => 'orange',
            self::TELLER => 'red',
            self::PLAYER => 'green',
        };
    }
}
