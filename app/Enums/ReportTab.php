<?php

namespace App\Enums;

enum ReportTab: string
{
    case EVENT = 'event';
    case TELLER = 'teller';

    public function label(): string
    {
        return match ($this) {
            self::EVENT => 'Event',
            self::TELLER => 'Teller',
        };
    }

    public function component(): string
    {
        return match ($this) {
            self::EVENT => 'report.event-report',
            self::TELLER => 'report.teller-report',
        };
    }


}
