<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin;

use App\Actions\User\UpsertUserAction;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use Flux\Flux;
use Illuminate\Http\Response;
use Livewire\Attributes\On;
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

    public function addWallet(int $id)
    {

        $this->dispatch('add-wallet', $id);
    }

    public function remit(int $id)
    {

        $this->dispatch('remit', $id);
    }

    public function view(int $id)
    {

        $this->dispatch('view-wallet', $id);
    }

    public function openFormModal(?User $user): void
    {
        $this->form->reset();

        if ($user->exists) {
            $this->form->setUser($user);
        }

        Flux::modal('user-form')->show();
    }

    public function save(UpsertUserAction $actions): Response
    {
        $this->form->validate();

        $user = $this->form->user;

        if (! ($user instanceof User)) {
            $user = new User();
        }

        $actions->handle($user, $this->form);
        $this->form->reset();
        Flux::toast('User successfully saved!', variant: 'success');
        Flux::modal('user-form')->close();

        return response()->noContent();
    }

    #[On('user-refresh')]
    public function userData()
    {
        $query = User::query();
        $query = $this->applySorting($query, 'username');
        $query = $this->applySearch($query, ['username']);

        return $query->paginate(10);
    }

    public function render()
    {
        $users = $this->userData();

        return view('livewire.dashboard.admin.user-index', [
            'users' => $users,
        ])->title('Users');
    }
}
