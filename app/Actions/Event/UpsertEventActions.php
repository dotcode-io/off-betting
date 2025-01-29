<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Livewire\Forms\EventForm;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

final class UpsertEventActions
{
    public function handle(Event $event, EventForm $form): Event
    {
        return DB::transaction(function () use ($form, $event) {

            $event->name = $form->name;
            $event->date = $form->date;
            $event->start_of_game = $form->start_of_game;
            $event->number_of_games = $form->number_of_games;
            $event->save();

            return $event;
        });
    }
}
