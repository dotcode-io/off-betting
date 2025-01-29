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

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::OPENED => 'green',
            self::CLOSED => 'red',
        };
    }

    public function isEditable(): bool
    {
        return $this === self::PENDING || $this === self::OPENED;
    }

    public function disabledOpen(): bool
    {
        return $this !== self::PENDING;
    }

    public function disabledClose(): bool
    {
        return $this !== self::OPENED;
    }
}
