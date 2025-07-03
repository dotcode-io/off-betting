<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

final class BetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required', 'numeric', 'min:1', 'max:100000', 'regex:/^\d*(\.\d{1,2})?$/',
            'side' => 'required', 'string', 'in:meron,wala,draw',
            'has_printer' => 'required|boolean',
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
            'ref' => [
                'required_if:has_printer,false',
                'string',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('manual_refs')
                        ->where('ref', $value)
                        ->where('used', false)
                        ->exists();

                    if (! $exists) {
                        $fail('The reference is either invalid or already used.');
                    }
                },
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
