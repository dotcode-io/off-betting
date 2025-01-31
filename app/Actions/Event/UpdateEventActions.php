<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Livewire\Forms\EventForm;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

final class UpdateEventActions
{
    /**
     * @throws Exception
     */
    public function handle(EventForm $form, Event $event): Event
    {

        if (! $event->status->isEditable()) {
            throw new Exception('Event is not editable');
        }

        return DB::transaction(fn () => $event->update([
            'name' => $form->name,
            'date' => $form->date,
            'start_of_game' => $form->start_of_game,
            'number_of_games' => $form->number_of_games,
        ]));
    }
}
