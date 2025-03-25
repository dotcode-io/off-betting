<?php

namespace App\Enums;

enum WalletLogType: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';

    public function label(): string
    {
        return match ($this) {
            self::CREDIT => 'Credit',
            self::DEBIT => 'Debit',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::CREDIT => 'green',
            self::DEBIT => 'red',
        };
    }

}
