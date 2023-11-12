<?php

namespace App\Http\Livewire\EventType;

use Livewire\Component;
use App\Models\EventType;
use App\Models\Season;
use App\Models\VenueArea;
use Illuminate\Support\Facades\Session;

class AddEventTypeModal extends Component
{
    public $event_name;
    public $selectedEventNames = [];
    public $typical_seating;
    public $duration_type;
    public $description;
    public $min_duration;
    public $max_duration;
    public $time_setup;
    public $time_cleaningup;
    public $seasons;
    public $selectedSeasons = [];
    public $min_people;
    public $max_people;
    public $opening_time;
    public $closing_time;
    public $venue_area_id;

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
            'selectedEventNames' => 'required|array',
            'selectedEventNames.*' => 'required|string|max:255',
            'typical_seating' => 'required|string',
            'duration_type' => 'required|in:days,hours',
            'description' => 'required|string|max:255',
            'min_duration' => 'required|integer',
            'max_duration' => 'required|integer',
            'time_setup' => 'required|integer',
            'time_cleaningup' => 'required|integer',
            'selectedSeasons' => 'required|array',
            'selectedSeasons.*' => 'integer',
            'min_people' => 'required|integer',
            'max_people' => 'required|integer',
            'opening_time' => 'required|string|max:255',
            'closing_time' => 'required|string|max:255',
            'venue_area_id' => 'required|exists:venue_areas,id',
        ]);

        $eventNames = implode(', ', $this->selectedEventNames);
        $seasons = implode(', ', $this->selectedSeasons);

        if ($this->edit_mode) {
            // If in edit mode, update the existing event type record
            $eventType = EventType::find($this->eventTypeId);
            $eventType->update([
                'name' => $eventNames,
                'event_name' => $this->event_name,
                'typical_seating' => $this->typical_seating,
                'duration_type' => $this->duration_type,
                'description' => $this->description,
                'min_duration' => $this->min_duration,
                'max_duration' => $this->max_duration,
                'time_setup' => $this->time_setup,
                'time_cleaningup' => $this->time_cleaningup,
                'seasons' => $seasons,
                'min_people' => $this->min_people,
                'max_people' => $this->max_people,
                'opening_time' => $this->opening_time,
                'closing_time' => $this->closing_time,
                'venue_area_id' => $this->venue_area_id,
            ]);

            // Emit an event to notify that the event type was updated successfully
            $this->emit('success', 'Event Type successfully updated');
        } else {
            // Save the new event type to the database
            EventType::create([
                'name' => implode(', ', $this->selectedEventNames),
                'event_name' => $this->event_name,
                'typical_seating' => $this->typical_seating,
                'duration_type' => $this->duration_type,
                'description' => $this->description,
                'min_duration' => $this->min_duration,
                'max_duration' => $this->max_duration,
                'time_setup' => $this->time_setup,
                'time_cleaningup' => $this->time_cleaningup,
                'seasons' => implode(', ', $this->selectedSeasons), // Store the selected seasons as a comma-separated string
                'min_people' => $this->min_people,
                'max_people' => $this->max_people,
                'opening_time' => $this->opening_time,
                'closing_time' => $this->closing_time,
                'venue_area_id' => $this->venue_area_id,
            ]);

            // Emit an event to notify that the event type was created successfully
            $this->emit('success', 'Event Type successfully added');

            // Reset the form fields
            $this->reset([
                'event_name',
                'typical_seating',
                'selectedEventNames',
                'duration_type',
                'description',
                'min_duration',
                'max_duration',
                'time_setup',
                'time_cleaningup',
                'selectedSeasons',
                'min_people',
                'opening_time',
                'closing_time',
                'venue_area_id',
            ]);
        }
    }

    public function render()
    {
        $currentTenantId = Session::get('current_tenant_id');
        $seasonsList = Season::where('tenant_id', $currentTenantId)->get();
        $venueAreas = VenueArea::where('tenant_id', $currentTenantId)->get();

        return view('livewire.event-type.add-event-type-modal', compact('seasonsList','venueAreas'));
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
        $this->selectedEventNames = explode(', ', $eventType->name);
        $this->typical_seating = $eventType->typical_seating;
        $this->duration_type = $eventType->duration_type;
        $this->description = $eventType->description;
        $this->min_duration = $eventType->min_duration;
        $this->max_duration = $eventType->max_duration;
        $this->time_setup = $eventType->time_setup;
        $this->time_cleaningup = $eventType->time_cleaningup;
        $this->selectedSeasons = explode(', ', $eventType->seasons);
        $this->min_people = $eventType->min_people;
        $this->max_people = $eventType->max_people;
        $this->opening_time = $eventType->opening_time;
        $this->closing_time = $eventType->closing_time;
        $this->eventTypeId = $eventType->id;
        $this->venue_area_id = $eventType->venue_area_id;
    }

}
