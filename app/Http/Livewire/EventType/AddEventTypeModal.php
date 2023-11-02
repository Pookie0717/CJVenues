<?php

namespace App\Http\Livewire\EventType;


use Livewire\Component;
use App\Models\EventType;
use App\Models\Season;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class AddEventTypeModal extends Component
{
    public $name;
    public $event_name;
    public $selectedEventNames = [];
    public $typical_seating;
    public $duration_type;
    public $duration;
    public $min_duration;
    public $time_setup;
    public $time_cleaningup;
    public $season_id;
    public $availability;

    public $edit_mode = false;
    public $eventTypeId;

    protected $listeners = [
        'delete_event_type' => 'deleteEventType',
        'update_event_type' => 'updateEventType',
    ];

    public function submit()
    {
        // Validate the data
        $this->validate([
            'event_name' => 'required|string|max:255',
            'selectedEventNames' => 'required|array', // Change to selectedEventNames
            'selectedEventNames.*' => 'required|string|max:255', // Validate each item in the array
            'typical_seating' => 'required',
            'duration_type' => 'required|in:days,hours,minutes',
            'duration' => 'required|integer',
            'min_duration' => 'required|integer',
            'time_setup' => 'required|integer',
            'time_cleaningup' => 'required|integer',
            'season_id' => 'required',
            'availability' => 'required|string|max:255',
        ]);

        $eventNames = implode(', ', $this->selectedEventNames);

        if ($this->edit_mode) {
            // If in edit mode, update the existing event type record
            $eventType = EventType::find($this->eventTypeId);
            $eventType->update([
                'name' => $eventNames,
                'event_name' => $this->event_name,
                'typical_seating' => $this->typical_seating,
                'duration_type' => $this->duration_type,
                'duration' => $this->duration,
                'min_duration' => $this->min_duration,
                'time_setup' => $this->time_setup,
                'time_cleaningup' => $this->time_cleaningup,
                'season_id' => $this->season_id,
                'availability' => $this->availability,
            ]);

            // Emit an event to notify that the event type was updated successfully
            $this->emit('success', 'Event Type successfully updated');

        } else {
            // Save the new event type to the database
            EventType::create([
                'name' => $eventNames,
                'event_name' => $this->event_name,
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
            $this->reset(['name', 'typical_seating', 'event_name', 'duration_type', 'duration', 'min_duration', 'time_setup', 'time_cleaningup', 'season_id', 'availability']);
            }
    }

    public function render()
    {
        $currentTenantId = Session::get('current_tenant_id');
        $seasons = Season::where('tenant_id', $currentTenantId)->get();
        
        return view('livewire.event-type.add-event-type-modal', compact('seasons'));
    }

    public function deleteEventType($id)
    {
        // Find the event type by ID
        $eventType = EventType::find($id);

        // Delete the event type
        $eventType->delete();

        // Emit a success event with a message
        $this->emit('success', 'Event Type successfully deleted');
    }

    public function updateEventType($id)
    {
        $this->edit_mode = true;
        $eventType = EventType::find($id);

        $this->event_name = $eventType->event_name;
        $this->typical_seating = $eventType->typical_seating;
        $this->selectedEventNames = explode(', ', $eventType->name); // Initialize selected values
        $this->duration_type = $eventType->duration_type;
        $this->duration = $eventType->duration;
        $this->min_duration = $eventType->min_duration;
        $this->time_setup = $eventType->time_setup;
        $this->time_cleaningup = $eventType->time_cleaningup;
        $this->season_id = $eventType->season_id;
        $this->availability = $eventType->availability;
        $this->eventTypeId = $eventType->id;
    }


}
