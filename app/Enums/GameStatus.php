<?php

declare(strict_types=1);

namespace App\Enums;

enum GameStatus: string
{
    case PENDING = 'pending';
    case OPENED = 'open';
    case CLOSED = 'close';
    case DONE = 'done';
    case OnGoing = 'on-going';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::OPENED => 'Opened',
            self::CLOSED => 'Closed',
            self::DONE => 'Done',
            self::OnGoing => 'OnGoing',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::OPENED => 'green',
            self::CLOSED => 'red',
            self::DONE => 'blue',
            self::OnGoing => 'orange',
        };
    }
}
