<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Event;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class EventForm extends Form
{
    public ?Event $event = null;

    #[Validate('required', 'string', 'min:3', 'max:50')]
    public $name = '';

    #[Validate('required', 'date')]
    public $date = '';

    #[Validate('required', 'integer', 'min:1')]
    public $start_of_game = '';

    #[Validate('required', 'integer', 'min:1000','max:50000', 'regex:/^[1-9][0-9]*000$/')]
    public $charge = '';

    #[Validate('required', 'integer', 'min:1')]
    public $number_of_games = '';

    public function setEvent(Event $event): void
    {
        $this->event = $event;
        $this->name = $event->name;
        $this->date = $event->date->format('Y-m-d');
        $this->start_of_game = $event->start_of_game;
        $this->number_of_games = $event->number_of_games;
        $this->charge = $event->charge;
    }
}
