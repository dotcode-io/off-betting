<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin;

use App\Actions\User\UpsertUserActions;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use Flux\Flux;
use Illuminate\Http\Response;
use Livewire\Component;
use Livewire\WithPagination;

final class UserIndex extends Component
{
    use Searchable, Sortable, WithPagination;

    public UserForm $form;

    public function getMatches(): array
    {
        return [
            'username' => 'username',
        ];
    }

    public function openFormModal(?User $user): void
    {
        $this->form->reset();

        if ($user->exists) {
            $this->form->setUser($user);
        }

        Flux::modal('user-form')->show();
    }

    public function save(UpsertUserActions $actions): Response
    {
        $this->form->validate();

        $user = $this->form->user ?? new User();

        $actions->handle($user, $this->form);
        $this->form->reset();
        Flux::toast('User successfully saved!', variant: 'success');
        Flux::modal('user-form')->close();

        return response()->noContent();
    }

    public function render()
    {
        $query = User::query();
        $query = $this->applySorting($query, 'username');
        $query = $this->applySearch($query, ['username']);
        $users = $query->paginate(10);

        return view('livewire.dashboard.admin.user-index', [
            'users' => $users,
        ])->title('Users');
    }
}
