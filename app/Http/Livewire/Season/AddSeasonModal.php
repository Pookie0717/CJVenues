<?php

namespace App\Http\Livewire\Season;

use Livewire\Component;
use App\Models\Season;

class AddSeasonModal extends Component
{
    public $name;
    public $date_from;
    public $date_to;
    public $priority;
    public $overwrite_weekday;
    
    public function submit()
    {
        // Validate the data
        $this->validate([
            'name' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'priority' => 'required|max:255',
        ]);

        // Save the new season to the database
        Season::create([
            'name' => $this->name,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'priority' => $this->priority,
            'overwrite_weekday' => $this->overwrite_weekday,
        ]);

        // Emit an event to notify that the season was created successfully
        $this->emit('success');

        // Reset the form fields
        $this->reset(['name', 'date_from', 'date_to', 'priority', 'overwrite_weekday']);
    }

    public function render()
    {
        return view('livewire.season.add-season-modal');
    }
}
