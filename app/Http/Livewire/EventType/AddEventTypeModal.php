<?php

namespace App\Http\Livewire\EventType;


use Livewire\Component;
use App\Models\EventType;
use App\Models\Season;

use Illuminate\Validation\Rule;

class AddEventTypeModal extends Component
{
    public $name;
    public $typical_seating;
    public $duration_type;
    public $duration;
    public $min_duration;
    public $time_setup;
    public $time_cleaningup;
    public $season_id;
    public $availability;

    public $edit_mode = false;

    protected $listeners = [
        'delete_event_type' => 'deleteEventType',
        'update_event_type' => 'updateEventType',
    ];

    public function submit()
    {
        // Validate the data
        $this->validate([
            'name' => 'required|string|max:255',
            'typical_seating' => 'required',
            'duration_type' => 'required|in:days,hours,minutes',
            'duration' => 'required|integer',
            'min_duration' => 'required|integer',
            'time_setup' => 'required|integer',
            'time_cleaningup' => 'required|integer',
            'season_id' => 'required',
            'availability' => 'required|string|max:255',
        ]);

        // Save the new event type to the database
        EventType::create([
            'name' => $this->name,
            'typical_seating' => $this->typical_seating,
            'duration_type' => $this->duration_type,
            'duration' => $this->duration,
            'min_duration' => $this->min_duration,
            'time_setup' => $this->time_setup,
            'time_cleaningup' => $this->time_cleaningup,
            'season_id' => $this->season_id,
            'availability' => $this->availability,
        ]);

        // Emit an event to notify that the event type was created successfully
        $this->emit('success', 'Event Type successfully added');

        // Reset the form fields
        $this->reset(['name', 'typical_seating', 'duration_type', 'duration', 'min_duration', 'time_setup', 'time_cleaningup', 'season_id', 'availability']);
    }

    public function render()
    {
        // Load seasons for selection
        $seasons = Season::all();
        
        return view('livewire.event-type.add-event-type-modal', compact('seasons'));
    }

    public function deleteEventType($id)
    {
        // Delete the event type record with the specified ID
        EventType::destroy($id);

        // Emit a success event with a message
        $this->emit('success', 'Event Type successfully deleted');
    }

    public function updateEventType($id)
    {
        $this->edit_mode = true;
        $event_type = EventType::find($id);
    }

    private function resetFields()
    {
        $this->reset([
            'name',
            'typical_seating',
            'duration_type',
            'duration',
            'min_duration',
            'time_setup',
            'time_cleaningup',
            'season_id',
            'availability',
            'edit_mode',
        ]);
    }

    private function getAttributes()
    {
        return [
            'name' => $this->name,
            'typical_seating' => $this->typical_seating,
            'duration_type' => $this->duration_type,
            'duration' => $this->duration,
            'min_duration' => $this->min_duration,
            'time_setup' => $this->time_setup,
            'time_cleaningup' => $this->time_cleaningup,
            'season_id' => $this->season_id,
            'availability' => $this->availability,
        ];
    }
}
