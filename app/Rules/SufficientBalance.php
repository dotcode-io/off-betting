<?php

namespace App\Rules;

use App\Models\User;
use App\ValueObjects\Cents;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SufficientBalance implements ValidationRule
{

    public function __construct(private readonly int $userId)
    {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //ignore if the user_id is not set
        $userWallet = User::findOrFail($this->userId);

        try {

            $value = (float) $value;
            $value = Cents::from($value)->getValue();
        } catch (\Exception $e) {
            $fail("Invalid amount");
        }
        if ($userWallet->balance <  $value ) {
            $fail("Insufficient balance from ".$userWallet->balance." to ".$value);
        }
    }
}


