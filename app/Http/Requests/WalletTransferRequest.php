<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\SufficientBalance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WalletTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */


    public function authorize(): bool
    {
        return true;
    }


    public function getUserToWallet(): User
    {
        return User::query()->where('uuid', $this->to_user)->firstOrFail();
    }

    public function getCurrentUserWallet(): User
    {
        return User::where('user_id', $this->user()->id)->firstOrFail();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'to_user' => ['required','string', 'exists:users,uuid'],
            'amount' => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,2})?$/', Rule::notIn([0]), new SufficientBalance($this->user()->id)],
        ];
    }
}
