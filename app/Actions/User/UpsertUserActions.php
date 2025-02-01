<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\UserStatus;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class UpsertUserActions
{
    public function handle(User $user, UserForm $form): User
    {
        return DB::transaction(function () use ($form, $user): User {

            $user->username = $form->username;
            $user->user_type = mb_strtolower((string) $form->user_type);
            $user->status = UserStatus::ACTIVE;

            if (! empty($form->password)) {
                $user->password = Hash::make($form->password);
            }

            $user->save();

            return $user;
        });
    }
}
