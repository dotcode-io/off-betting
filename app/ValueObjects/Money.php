<?php

namespace App\ValueObjects;

class Money
{

    /**
     * Create a new class instance.
     */
    public function __construct(private readonly int $valueInCents)
    {
        
    }

    public static function from(int $valueInCents): self
    {
        return new self($valueInCents);
    }

    public function amount(): array 
    {
        return [
            'value' => $this->valueInCents / 100,
            'currency' => 'PHP'
        ];
    }

    public function toCents(): int
    {
        return $this->valueInCents;
    }
}


