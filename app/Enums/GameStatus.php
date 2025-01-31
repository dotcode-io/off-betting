<?php

namespace App\Enums;

enum GameStatus: string
{

    case PENDING = 'pending';
    case OPENED = 'open';
    case CLOSED = 'close';
    case DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::OPENED => 'Opened',
            self::CLOSED => 'Closed',
            self::DONE => 'Done',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::OPENED => 'green',
            self::CLOSED => 'red',
            self::DONE => 'blue',
        };
    }
}
