<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1">Game Controller</flux:heading>


        </div>
        <div>

        </div>
    </div>
    <flux:separator variant="subtle"/>

    <div class="grid grid-cols-3 gap-4 py-3">
        <div class="flex flex-col space-y-2">
            <flux:card class="space-y-6">
                <div class="flex">
                    <div class="flex-1">
                        <flux:heading size="xl">{{ $event->name }}</flux:heading>
                        <div class="py-2">
                            <flux:separator variant="subtle"/>
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
                            <flux:separator variant="subtle"/>
                        </div>
                        <flux:heading size="lg">Date Opened:</flux:heading>
                        <flux:subheading>{{ $event->openedAtFormated()}}</flux:subheading>
                        <div class="py-2">
                            <flux:separator variant="subtle"/>
                        </div>
                        <flux:heading size="lg">Date Closed:</flux:heading>
                        <flux:subheading>{{$event->closedAtFormated()}}</flux:subheading>
                        <div class="py-2">
                            <flux:separator variant="subtle"/>
                        </div>
                        <flux:heading size="lg">Actions</flux:heading>
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
                            <flux:separator variant="subtle"/>
                        </div>


                    </div>

                </div>

            </flux:card>


        </div>
        <div>
            <x-game-controller.main :games="$games">

                <flux:card class="space-y-6">
                    <x-stats.results-card/>
                </flux:card>
            </x-game-controller.main>
        </div>
        <div class="flex flex-col space-y-2">
            <flux:card class="space-y-6">
                <div class="flex">
                    <div class="flex-1">
                        <flux:heading size="lg">Fight #70</flux:heading>
                        <div class="py-2">
                            <flux:separator variant="subtle"/>
                        </div>

                        <div>
                            <flux:heading size="lg">Game Controller</flux:heading>
                            <div class="flex items-center space-x-2 py-2">
                                <flux:button class="!bg-green-500 w-full">Opened</flux:button>
                                <flux:button variant="danger" class="w-full">Closed</flux:button>
                            </div>

                        </div>
                        <div>
                            <flux:heading size="lg">Game Result</flux:heading>
                            <flux:select variant="listbox" placeholder="Select Result...">
                                @foreach (\App\Enums\ResultEnum::cases() as $result)

                                    @if($result !== \App\Enums\ResultEnum::CANCELLED)
                                        <flux:option value="{{ $result->value}}">
                                            <div class="flex items-center gap-2">
                                                <div class="size-4 rounded-full bg-{{ $result->color()}}-500">
                                                </div>
                                                <div>
                                                    {{ $result->label() }}
                                                </div>
                                            </div>

                                        </flux:option>
                                    @endif
                                @endforeach

                            </flux:select>
                            <div class="flex items-center space-x-2 py-2">
                                <flux:button class="!bg-green-500 w-full">Declare</flux:button>
                                <flux:button variant="danger" class="w-full">Cancelled</flux:button>
                            </div>

                        </div>
                    </div>


                </div>

            </flux:card>
            <flux:card class="space-y-6">
                <div class="flex">
                    <div class="flex-1">
                        <flux:heading size="lg">Fight #70</flux:heading>
                        <div class="py-2">
                            <flux:separator variant="subtle"/>
                        </div>

                        <div>
                            <flux:heading size="lg">Game Controller</flux:heading>
                            <div class="flex items-center space-x-2 py-2">
                                <flux:button class="!bg-green-500 w-full">Opened</flux:button>
                                <flux:button variant="danger" class="w-full">Closed</flux:button>
                            </div>

                        </div>
                        <div>
                            <flux:heading size="lg">Game Result</flux:heading>
                            <flux:select variant="listbox" placeholder="Select Result...">
                                @foreach (\App\Enums\ResultEnum::cases() as $result)

                                    @if($result !== \App\Enums\ResultEnum::CANCELLED)
                                        <flux:option value="{{ $result->value}}">
                                            <div class="flex items-center gap-2">
                                                <div class="size-4 rounded-full bg-{{ $result->color()}}-500">
                                                </div>
                                                <div>
                                                    {{ $result->label() }}
                                                </div>
                                            </div>

                                        </flux:option>
                                    @endif
                                @endforeach

                            </flux:select>
                            <div class="flex items-center space-x-2 py-2">
                                <flux:button class="!bg-green-500 w-full">Declare</flux:button>
                                <flux:button variant="danger" class="w-full">Cancelled</flux:button>
                            </div>

                        </div>
                    </div>


                </div>

            </flux:card>

            <flux:card class="space-y-6">
                <div class="flex">
                    <div class="flex-1">
                        <flux:heading size="lg">Fight #70</flux:heading>
                        <div class="py-2">
                            <flux:separator variant="subtle"/>
                        </div>

                        <div>
                            <flux:heading size="lg">Game Controller</flux:heading>
                            <div class="flex items-center space-x-2 py-2">
                                <flux:button class="!bg-green-500 w-full">Opened</flux:button>
                                <flux:button variant="danger" class="w-full">Closed</flux:button>
                            </div>

                        </div>
                        <div>
                            <flux:heading size="lg">Game Result</flux:heading>
                            <flux:select variant="listbox" placeholder="Select Result...">
                                @foreach (\App\Enums\ResultEnum::cases() as $result)

                                    @if($result !== \App\Enums\ResultEnum::CANCELLED)
                                        <flux:option value="{{ $result->value}}">
                                            <div class="flex items-center gap-2">
                                                <div class="size-4 rounded-full bg-{{ $result->color()}}-500">
                                                </div>
                                                <div>
                                                    {{ $result->label() }}
                                                </div>
                                            </div>

                                        </flux:option>
                                    @endif
                                @endforeach

                            </flux:select>
                            <div class="flex items-center space-x-2 py-2">
                                <flux:button class="!bg-green-500 w-full">Declare</flux:button>
                                <flux:button variant="danger" class="w-full">Cancelled</flux:button>
                            </div>

                        </div>
                    </div>


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
            <flux:spacer/>

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
            <flux:spacer/>

            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <form wire:submit="closeEvent">

                <flux:button type="submit" variant="primary">Closed event</flux:button>
            </form>
        </div>
    </flux:modal>


</div>
