<?php

declare(strict_types=1);

namespace App\Enums;

enum EventStatus: string
{
    case PENDING = 'pending';
    case OPENED = 'opened';
    case CLOSED = 'closed';

    /**
     * Get the label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::OPENED => 'Opened',
            self::CLOSED => 'Closed',
        };
    }
}
