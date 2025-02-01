<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Form;

final class UserForm extends Form
{
    public ?User $user = null;

    public $username = '';

    public $user_type = '';

    public $password = '';

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->username = $user->username;
        $this->user_type = mb_strtolower((string) $user->user_type->label());
        $this->password = '';
    }

    public function rules(): array
    {
        return [
            'username' => [
                'bail',
                'required',
                'string',
                'min:3',
                'max:50',
                "unique:users,username,{$this->user?->id}",
            ],
            'user_type' => 'required|in:admin,teller,controller',
            'password' => $this->user instanceof User ? 'nullable|string|min:6' : 'required|string|min:6',
        ];
    }
}
