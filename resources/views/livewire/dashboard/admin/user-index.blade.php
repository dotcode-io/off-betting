<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Users</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
    <div class="flex p-4 items-center space-x-2">
        <div class=" w-2/6">
            <flux:input icon="magnifying-glass" placeholder="Search events" wire:model.live.debounce='search' />
        </div>

        <div class="">
            <flux:button variant="filled" icon-trailing="plus" wire:click="openFormModal()"> Create new user
            </flux:button>
        </div>

    </div>
    <flux:table :paginate="$users">
        <flux:table.columns>
            <flux:table.column>Username</flux:table.column>
            <flux:table.column>User Type</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($users as $user)
            <flux:table.rows :key="$user->id">
                <flux:table.cell>{{ $user->username }}</flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="{{ $user->user_type->color() }}" size="sm" inset="top bottom">
                        {{ $user->user_type->label() }}
                    </flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="{{ $user->status->color() }}" size="sm" inset="top bottom">
                        {{ $user->status->label() }}
                    </flux:badge>
                </flux:table.cell>
                <flux:table.cell>

                    <span class="text-green-500"> {{ number_format($user->wallet_amount,2) }}</span>
                </flux:table.cell>
                <flux:table.cell class="flex space-x-2 items-center justify-center">
                    <flux:button size="sm" :disabled="$user->id === auth()->id()" wire:click="openFormModal('{{ $user->uuid }}')">Edit</flux:button>
                   @if($user->user_type === \App\Enums\UserType::TELLER)
                        <flux:button size="sm" variant="primary" wire:click="addWallet({{$user->id}})">Add Wallet</flux:button>
                        <flux:button size="sm" variant="danger" wire:click="remit({{$user->id}})">Remit</flux:button>
                        <flux:button size="sm" variant="primary" wire:click="view({{$user->id}})">View</flux:button>
                    @endif
                </flux:table.cell>


            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:modal name="user-form" class="md:w-96 space-y-6">
        <form wire:submit='save'>
            <div>
                <flux:heading size="lg" class="mb-4">User Form</flux:heading>
            </div>
            <flux:input label="Username" placeholder="Username" wire:model="form.username" class="mb-3" />
            <flux:select label="User Type" variant="listbox" placeholder="Select user type..." wire:model="form.user_type" class="mb-3">
                <flux:select.option value="admin">
                    <div class="flex items-center gap-2">
                        <flux:icon.shield-check variant="mini" class="text-zinc-400" /> Admin
                    </div>
                </flux:select.option>

                <flux:select.option value="teller">
                    <div class="flex items-center gap-2">
                        <flux:icon.key variant="mini" class="text-zinc-400" /> Teller
                    </div>
                </flux:select.option>

                <flux:select.option value="controller">
                    <div class="flex items-center gap-2">
                        <flux:icon.user variant="mini" class="text-zinc-400" /> Controller
                    </div>
                </flux:select.option>
            </flux:select>
            <flux:input label="Password" type="password" placeholder="Password" wire:model="form.password" class="mb-3" viewable />
            <div class="flex pt-2">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save User</flux:button>
            </div>
        </form>
    </flux:modal>

    <livewire:add-wallet-modal />
    <livewire:remit-modal />
    <livewire:view-wallet-modal />

</div>
