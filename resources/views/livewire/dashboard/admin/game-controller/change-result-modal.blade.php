<flux:modal name="change-result-modal" class="md:w-96">
   @if($game)
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Change Result</flux:heading>
                <flux:text class="mt-2">Changing of game result</flux:text>
            </div>
            <flux:input label="Fight Number" value="{{ $game->game_number }}" readonly/>
            <flux:input label="Current Result" value="{{ $game->result->label() }}" readonly/>
            <div>
                <flux:heading size="lg">Result to be Update</flux:heading>
                <flux:select variant="listbox" placeholder="Select Result..." wire:model="resultSelected">

                    @foreach (\App\Enums\GameResult::cases() as $result)

                        @if($result !== \App\Enums\GameResult::PENDING && $result !== $game->result)
                            <flux:select.option value="{{ $result->value}}">
                                <div class="flex items-center gap-2">
                                    <div class="size-4 rounded-full bg-{{ $result->color()}}-500">
                                    </div>
                                    <div class="uppercase">
                                        {{ $result->label() }}
                                    </div>
                                </div>

                            </flux:select.option>
                        @endif
                    @endforeach
                </flux:select>

            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="save()">Change</flux:button>
            </div>
        </div>
   @endif
</flux:modal>
