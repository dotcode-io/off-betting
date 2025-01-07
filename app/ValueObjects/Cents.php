<?php

namespace App\ValueObjects;

class Cents
{

    public function __construct(private readonly int $value)
    {
       
    }

    public static function from(float $value): self
    {
        //throw an error if the float value  has more than 2 decimal places
        if (strpos($value, '.') !== false && strlen(explode('.', $value)[1]) > 2) {
            throw new \Exception("Invalid amount");
        }
       
        return new self((int) ($value * 100));
    }

    public function getValue(): int
    {
        return $this->value;
    }

}

