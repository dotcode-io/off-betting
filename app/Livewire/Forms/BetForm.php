<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class BetForm extends Form
{
    #[Validate('required', 'numeric', 'min:1', 'max:100000', 'regex:/^\d*(\.\d{1,2})?$/')]
    public $amount = '';

    #[Validate('required', 'string', 'in:meron,wala,draw')]
    public $side = '';

    public $idempotency_key = '';

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1|max:100000|regex:/^\d*(\.\d{1,2})?$/',
            'side' => 'required|string|in:meron,wala,draw',
            'idempotency_key' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('bets')
                        ->where('idempotency_key', $value)
                        ->exists();

                    if ($exists) {
                        $fail('This request has already been processed. Please use a unique idempotency key.');
                    }
                },
            ],
        ];
    }

    public function generateIdempotencyKey(): void
    {
        $this->idempotency_key = 'livewire-' . uniqid() . '-' . time();
    }
}
