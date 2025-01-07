<?php

namespace App\Enums;

enum EventStatus: string
{

    case PENDING = 'pending';
    case OPENED = 'opened';
    case CLOSED = 'closed';


    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::OPENED => 'Opened',
            self::CLOSED => 'Closed',
        };
    }
}
