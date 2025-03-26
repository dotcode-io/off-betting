<?php

namespace App\Enums;

enum ReportTab: string
{
    case EVENT = 'event';
    case TELLER = 'teller';
    case GB = 'gb';

    public function label(): string
    {
        return match ($this) {
            self::EVENT => 'Event',
            self::TELLER => 'Teller',
            self::GB => 'Ghost Bet',
        };
    }

    public function component(): string
    {
        return match ($this) {
            self::EVENT => 'report.event-report',
            self::TELLER => 'report.teller-report',
            self::GB => 'report.gb-report',
        };
    }


}
