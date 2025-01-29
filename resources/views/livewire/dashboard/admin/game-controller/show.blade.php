<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1">Game Controller</flux:heading>


        </div>
        <div>

        </div>
    </div>
    <flux:separator variant="subtle" />

    <div class="grid grid-cols-3 gap-4 py-3">
        <div class="flex flex-col space-y-2">
            <flux:card class="space-y-6">
                <div class="flex">
                    <div class="flex-1">
                        <flux:heading size="xl">{{ $event->name }}</flux:heading>
                        <div class="py-2">
                            <flux:separator variant="subtle" />
                        </div>
                        <div class="flex items-center space-x-2">
                            <flux:heading size="lg">Status:
                            </flux:heading>
                            <div>
                                <flux:badge color="{{ $event->status->color() }}" size="sm">
                                    {{ $event->status->label() }}
                                </flux:badge>
                            </div>
                        </div>
                        <div class="py-2">
                            <flux:separator variant="subtle" />
                        </div>
                        <flux:heading size="lg">Date Opened: </flux:heading>
                        <flux:subheading>{{ $event->openedAtFormated()}}</flux:subheading>
                        <div class="py-2">
                            <flux:separator variant="subtle" />
                        </div>
                        <flux:heading size="lg">Date Closed: </flux:heading>
                        <flux:subheading>{{$event->closedAtFormated()}}</flux:subheading>
                        <div class="py-2">
                            <flux:separator variant="subtle" />
                        </div>
                        <flux:heading size="lg">Actions </flux:heading>
                        <div>
                            <flux:modal.trigger name="open-event">
                                <flux:button variant="filled" :disabled="$event->status->disabledOpen() ">Opened
                                </flux:button>
                            </flux:modal.trigger>
                        <flux:modal.trigger name="close-event">
                            <flux:button variant="danger" :disabled="$event->status->disabledClose() ">Closed
                            </flux:button>
                            </flux:modal.trigger>
                        </div>
                        <div class="py-2">
                            <flux:separator variant="subtle" />
                        </div>




                    </div>

                </div>

            </flux:card>
            <flux:card class="space-y-6">
                <div class="flex">
                    <div class="flex-1">
                        <flux:heading size="lg">Are you sure?</flux:heading>

                        <flux:subheading>
                            <p>Your post will be deleted permanently.</p>
                            <p>This action cannot be undone.</p>
                        </flux:subheading>
                    </div>

                    <div class="-mx-2 -mt-2">
                        <flux:button variant="ghost" size="sm" icon="x-mark" inset="top right bottom" />
                    </div>
                </div>

                <div class="flex gap-4">
                    <flux:spacer />
                    <flux:button variant="ghost">Undo</flux:button>
                    <flux:button variant="danger">Delete</flux:button>
                </div>
            </flux:card>
        </div>
        <div>
            <flux:card class="space-y-6">
                <div class="flex">
                    <div class="flex-1">
                        <flux:heading size="lg">Are you sure?</flux:heading>

                        <flux:subheading>
                            <p>Your post will be deleted permanently.</p>
                            <p>This action cannot be undone.</p>
                        </flux:subheading>
                    </div>

                    <div class="-mx-2 -mt-2">
                        <flux:button variant="ghost" size="sm" icon="x-mark" inset="top right bottom" />
                    </div>
                </div>

                <div class="flex gap-4">
                    <flux:spacer />
                    <flux:button variant="ghost">Undo</flux:button>
                    <flux:button variant="danger">Delete</flux:button>
                </div>
            </flux:card>
        </div>
        <div>
            <flux:card class="space-y-6">
                <div class="flex">
                    <div class="flex-1">
                        <flux:heading size="lg">Are you sure?</flux:heading>

                        <flux:subheading>
                            <p>Your post will be deleted permanently.</p>
                            <p>This action cannot be undone.</p>
                        </flux:subheading>
                    </div>

                    <div class="-mx-2 -mt-2">
                        <flux:button variant="ghost" size="sm" icon="x-mark" inset="top right bottom" />
                    </div>
                </div>

                <div class="flex gap-4">
                    <flux:spacer />
                    <flux:button variant="ghost">Undo</flux:button>
                    <flux:button variant="danger">Delete</flux:button>
                </div>
            </flux:card>
        </div>
    </div>


    <flux:modal name="open-event" class="min-w-[22rem] space-y-6">
        <div>
            <flux:heading size="lg">Opened event?</flux:heading>

            <flux:subheading>
                <p>You're about to opened this event.</p>
            </flux:subheading>
        </div>

        <div class="flex gap-2">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <form wire:submit="openEvent">

                <flux:button type="submit" variant="primary">Opened event</flux:button>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="close-event" class="min-w-[22rem] space-y-6">
        <div>
            <flux:heading size="lg">Closed event?</flux:heading>

            <flux:subheading>
                <p>You're about to closed this event.</p>
            </flux:subheading>
        </div>

        <div class="flex gap-2">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <form wire:submit="closeEvent">

                <flux:button type="submit" variant="primary">Closed event</flux:button>
            </form>
        </div>
    </flux:modal>





</div>
