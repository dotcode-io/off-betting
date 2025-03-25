<?php

namespace App\Livewire\Report;

use App\Models\Event;
use Livewire\Component;

class ReportIndex extends Component
{

    public Event $event;
    public string $selectedTab = 'fight';
    public function mount(Event $event)
    {
        $this->event = $event;
    }
    public function render()
    {
        return view('livewire.report.report-index');
    }
}
