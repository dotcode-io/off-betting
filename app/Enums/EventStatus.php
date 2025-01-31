<?php

declare(strict_types=1);

namespace App\Enums;

enum EventStatus: string
{
    case PENDING = 'pending';
    case OPENED = 'open';
    case CLOSED = 'close';
    case DONE = 'done';

    /**
     * Get the label for the status.
     */
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

    public function isOpened(): bool
    {
        return $this === self::OPENED;
    }

    public function isClosed(): bool
    {
        return $this === self::CLOSED;
    }

    public function isDone(): bool
    {
        return $this === self::DONE;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}
