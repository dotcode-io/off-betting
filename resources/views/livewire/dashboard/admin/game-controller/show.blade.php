<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Game Controller</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />

    <x-game-controller.main :games="$games" :game="$game">
        <div class="grid  grid-cols-1 md:grid-cols-3 gap-1 md:gap-4 py-3">
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
                            <flux:heading size="lg">Date Opened:</flux:heading>
                            <flux:subheading>{{ $event->openedAtFormated()}}</flux:subheading>
                            <div class="py-2">
                                <flux:separator variant="subtle" />
                            </div>
                            <flux:heading size="lg">Date Closed:</flux:heading>
                            <flux:subheading>{{$event->closedAtFormated()}}</flux:subheading>
                            <div class="py-2">
                                <flux:separator variant="subtle" />
                            </div>
                            <div>
                                @if($event->status->isPending())
                                <flux:modal.trigger name="open-event">
                                    <flux:button variant="primary" class="w-full" :disabled="$event->status->disabledOpen() ">Open Event
                                    </flux:button>
                                </flux:modal.trigger>
                                @endif
                                @if($event->status->isOpened())
                                <flux:modal.trigger name="close-event">
                                    <flux:button variant="danger" class="w-full" :disabled="$event->status->disabledClose() ">Close Event
                                    </flux:button>
                                </flux:modal.trigger>
                                @endif
                            </div>

                        </div>

                    </div>

                </flux:card>


            </div>

            @if($game)
            <div class="space-y-2">
                <x-stats.betting />
                <x-stats.results-count-card />
            </div>
            @endif


            @if($game)
            <div class="flex flex-col space-y-2">
                <flux:card class="space-y-6">
                    <div class="flex">
                        <div class="flex-1">
                            <flux:heading size="lg">Fight #<span x-text="game.game_number"> </span></flux:heading>
                            <div class="py-2">
                                <flux:separator variant="subtle" />
                            </div>

                            <div>
                                <flux:heading size="lg">Game Controller</flux:heading>
                                <div class="flex items-center space-x-2 py-2">
                                    <flux:modal.trigger name="open-game">
                                        <flux:button class="!bg-green-500 w-full" x-bind:disabled="game.status != '{{ \App\Enums\GameStatus::PENDING->label() }}'">Opened</flux:button>
                                    </flux:modal.trigger>
                                    <flux:modal.trigger name="close-game">
                                        <flux:button variant="danger" class="w-full" x-bind:disabled="game.status != '{{ \App\Enums\GameStatus::OPENED->label() }}'">Closed</flux:button>
                                    </flux:modal.trigger>
                                </div>

                            </div>
                            <div>
                                <flux:heading size="lg">Game Result</flux:heading>
                                <flux:select variant="listbox" placeholder="Select Result..." x-bind:disabled="game.status != '{{ \App\Enums\GameStatus::CLOSED->label() }}'" wire:model.live="resultSelected">
                                    @foreach (\App\Enums\GameResult::cases() as $result)

                                    @if($result !== \App\Enums\GameResult::CANCELLED && $result !== \App\Enums\GameResult::PENDING)
                                    <flux:option value="{{ $result->value}}">
                                        <div class="flex items-center gap-2">
                                            <div class="size-4 rounded-full bg-{{ $result->color()}}-500">
                                            </div>
                                            <div class="uppercase">
                                                {{ $result->label() }}
                                            </div>
                                        </div>

                                    </flux:option>
                                    @endif
                                    @endforeach

                                </flux:select>
                                <div class="flex items-center space-x-2 py-2">
                                    <flux:modal.trigger name="game-result">
                                        <flux:button class="!bg-green-500 w-full" x-bind:disabled="game.status != '{{ \App\Enums\GameStatus::CLOSED->label() }}'">Declare</flux:button>
                                    </flux:modal.trigger>
                                    <flux:button variant="danger" class="w-full" x-bind:disabled="game.status != '{{ \App\Enums\GameStatus::CLOSED->label() }}'" wire:click="cancelledGameModal">Cancelled</flux:button>
                                </div>

                            </div>
                        </div>


                    </div>

                </flux:card>
                <x-stats.results-card />
                <x-stats.streaks-card />
            </div>
            @endif


        </div>
        @if($game)
        <flux:modal name="open-game" class="min-w-[22rem] space-y-6">
            <div>
                <flux:heading size="lg">FIGHT # <span x-text="game.game_number"> </span></flux:heading>

                <flux:subheading>
                    <p>You're about to open a game.</p>
                </flux:subheading>
            </div>

            <form wire:submit="openGame">

                <flux:input type="text" label="MERON ENTRY NAME" wire:model="gameForm.meron_name" />
                <flux:input type="text" label="WALA ENTRY NAME" wire:model="gameForm.wala_name" />
                <div class="flex gap-2 pt-2">
                    <flux:spacer />


                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Opened Game</flux:button>
                </div>
            </form>

        </flux:modal>
        <flux:modal name="close-game" class="min-w-[22rem] space-y-6">
            <div>
                <flux:heading size="lg">FIGHT # <span x-text="game.game_number"> </span></flux:heading>

                <flux:subheading>
                    <p>You're about to close a game.</p>
                </flux:subheading>
            </div>

            <form wire:submit="closeGame">
                <div class="flex gap-2 pt-2">
                    <flux:spacer />


                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Close Game</flux:button>
                </div>
            </form>

        </flux:modal>

        <flux:modal name="game-result" class="min-w-[22rem] space-y-6">
            <div>
                <flux:heading size="lg">FIGHT # <span x-text="game.game_number"> </span></flux:heading>

                <flux:subheading>
                    <p>You're about to declare a game result.</p>
                </flux:subheading>

                <flux:spacer />
                @php
                $resultEnum = \App\Enums\GameResult::tryFrom($resultSelected)
                @endphp

                <div class="pt-2">
                    <flux:heading size="xl">RESULT: <span class="uppercase text-{{$resultEnum?->color()}}-500">{{ $resultEnum?->label()  }}</span></flux:heading>
                </div>
            </div>

            <form wire:submit="declareGameResult">
                <div class="flex gap-2 pt-2">
                    <flux:spacer />


                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Declare {{ $resultEnum?->label()  }}</flux:button>
                </div>
            </form>

        </flux:modal>
        @endif
    </x-game-controller.main>


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