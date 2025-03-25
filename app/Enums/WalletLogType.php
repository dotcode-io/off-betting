<?php

namespace App\Enums;

enum WalletLogType: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';

    public function label(): string
    {
        return match ($this) {
            self::CREDIT => 'Cash In',
            self::DEBIT => 'Cash Out',
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
