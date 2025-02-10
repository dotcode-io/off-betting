<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

final class BettingDataTransferObject
{
    public function __construct(
        public float $amount,
        public string $side,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'],
            side: $data['side'],
        );
    }
}
