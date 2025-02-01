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
        <flux:columns>
            <flux:column>Username</flux:column>
            <flux:column>User Type</flux:column>
            <flux:column>Status</flux:column>
            <flux:column></flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($users as $user)
            <flux:row :key="$user->id">
                <flux:cell>{{ $user->username }}</flux:cell>
                <flux:cell>
                    <flux:badge color="{{ $user->user_type->color() }}" size="sm" inset="top bottom">
                        {{ $user->user_type->label() }}
                    </flux:badge>
                </flux:cell>
                <flux:cell>
                    <flux:badge color="{{ $user->status->color() }}" size="sm" inset="top bottom">
                        {{ $user->status->label() }}
                    </flux:badge>
                </flux:cell>
                <flux:cell class="flex space-x-2 items-center justify-center">
                    <flux:button size="sm" :disabled="$user->id === auth()->id()" wire:click="openFormModal('{{ $user->uuid }}')">Edit</flux:button>
                    <flux:button size="sm">View</flux:button>
                </flux:cell>


            </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>

    <flux:modal name="user-form" class="md:w-96 space-y-6">
        <form wire:submit='save'>
            <div>
                <flux:heading size="lg" class="mb-4">User Form</flux:heading>
            </div>
            <flux:input label="Username" placeholder="Username" wire:model="form.username" class="mb-3" />
            <flux:select label="User Type" variant="listbox" placeholder="Select user type..." wire:model="form.user_type" class="mb-3">
                <flux:option value="admin">
                    <div class="flex items-center gap-2">
                        <flux:icon.shield-check variant="mini" class="text-zinc-400" /> Admin
                    </div>
                </flux:option>

                <flux:option value="teller">
                    <div class="flex items-center gap-2">
                        <flux:icon.key variant="mini" class="text-zinc-400" /> Teller
                    </div>
                </flux:option>

                <flux:option value="controller">
                    <div class="flex items-center gap-2">
                        <flux:icon.user variant="mini" class="text-zinc-400" /> Controller
                    </div>
                </flux:option>
            </flux:select>
            <flux:input label="Password" type="password" placeholder="Password" wire:model="form.password" class="mb-3" viewable />
            <div class="flex pt-2">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save User</flux:button>
            </div>
        </form>
    </flux:modal>

</div>