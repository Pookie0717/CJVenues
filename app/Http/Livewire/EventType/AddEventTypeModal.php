<?php

namespace App\Http\Livewire\EventType;

use Livewire\Component;
use App\Models\EventType;
use App\Models\Season;
use App\Models\VenueArea;
use App\Models\Tenant;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AddEventTypeModal extends Component
{
    public $tenant_id;
    public $name;
    public $event_name;
    public $selectedEventNames = [];
    public $typical_seating;
    public $duration_type;
    public $description;
    public $min_duration;
    public $max_duration;
    public $seasons;
    public $selectedSeasons = [];
    public $min_people;
    public $max_people;
    public $opening_time = "00:00";
    public $closing_time = "23:30";
    public $venue_area_id;
    public $min_buffer_before;
    public $max_buffer_before;
    public $min_buffer_after;
    public $max_buffer_after;

    public $edit_mode = false;
    public $eventTypeId;

    protected $listeners = [
        'create_event_type' => 'createEventType',
        'delete_event_type' => 'deleteEventType',
        'update_event_type' => 'updateEventType',
        'update_event_type_range' => 'updateEventTypeRange'
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
            'selectedSeasons' => 'required|array',
            'selectedSeasons.*' => 'integer',
            'min_people' => 'required|integer',
            'max_people' => 'required|integer',
            'opening_time' => 'required|string|max:255',
            'closing_time' => 'required|string|max:255',
            'venue_area_id' => 'required|exists:venue_areas,id',
            'min_buffer_before' => 'nullable|integer',
            'max_buffer_before' => 'nullable|integer',
            'min_buffer_after' => 'nullable|integer',
            'max_buffer_after' => 'nullable|integer',
        ]);

        $eventNames = implode(', ', $this->selectedEventNames);
        $seasons = implode(', ', $this->selectedSeasons);

        if ($this->edit_mode) {
            // If in edit mode, update the existing event type record
            $eventType = EventType::find($this->eventTypeId);
            $eventType->update([
                'tenant_id' => $this->tenant_id, 
                'name' => $eventNames,
                'event_name' => $this->event_name,
                'typical_seating' => $this->typical_seating,
                'duration_type' => $this->duration_type,
                'description' => $this->description,
                'min_duration' => $this->min_duration,
                'max_duration' => $this->max_duration,
                'seasons' => $seasons,
                'min_people' => $this->min_people,
                'max_people' => $this->max_people,
                'opening_time' => $this->opening_time,
                'closing_time' => $this->closing_time,
                'venue_area_id' => $this->venue_area_id,
                'min_buffer_before' => $this->min_buffer_before,
                'max_buffer_before' => $this->max_buffer_before,
                'min_buffer_after' => $this->min_buffer_after,
                'max_buffer_after' => $this->max_buffer_after,
            ]);

            // Emit an event to notify that the event type was updated successfully
            $this->emit('success', 'Event Package successfully updated');
        } else {
            // Save the new event type to the database
            EventType::create([
                'tenant_id' => $this->tenant_id, 
                'name' => implode(', ', $this->selectedEventNames),
                'event_name' => $this->event_name,
                'typical_seating' => $this->typical_seating,
                'duration_type' => $this->duration_type,
                'description' => $this->description,
                'min_duration' => $this->min_duration,
                'max_duration' => $this->max_duration,
                'seasons' => implode(', ', $this->selectedSeasons), // Store the selected seasons as a comma-separated string
                'min_people' => $this->min_people,
                'max_people' => $this->max_people,
                'opening_time' => $this->opening_time,
                'closing_time' => $this->closing_time,
                'venue_area_id' => $this->venue_area_id,
                'min_buffer_before' => $this->min_buffer_before,
                'max_buffer_before' => $this->max_buffer_before,
                'min_buffer_after' => $this->min_buffer_after,
                'max_buffer_after' => $this->max_buffer_after,
            ]);

            // Emit an event to notify that the event type was created successfully
            $this->emit('success', 'Event Package successfully added');

            // Reset the form fields
            $this->reset([
                'name',
                'event_name',
                'typical_seating',
                'selectedEventNames',
                'duration_type',
                'description',
                'min_duration',
                'max_duration',
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
        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;

        $seasonsList = Season::whereIn('tenant_id', $tenantIds)->get();
        $venueAreas = VenueArea::whereIn('tenant_id', $tenantIds)->get();

        return view('livewire.event-type.add-event-type-modal', compact('seasonsList','venueAreas'));
    }

    public function createEventType() {
        $this->edit_mode = false;
        $this->tenant_id = Session::get('current_tenant_id');
        $this->reset([
            'name',
            'event_name',
            'typical_seating',
            'selectedEventNames',
            'duration_type',
            'description',
            'min_duration',
            'max_duration',
            'selectedSeasons',
            'min_people',
            'opening_time',
            'closing_time',
            'venue_area_id',
        ]);
    }
    
    public function deleteEventType($id)
    {
        // Find the event type by ID
        $eventType = EventType::find($id);

        // Delete the event type
        $eventType->delete();

        // Emit a success event with a message
        $this->emit('success', 'Event Package successfully deleted');
    }

    public function updateEventType($id)
    {
        $this->edit_mode = true;
        $eventType = EventType::find($id);

        $this->tenant_id = $eventType->tenant_id;

        $this->event_name = $eventType->event_name;
        $this->selectedEventNames = explode(', ', $eventType->name);
        $this->typical_seating = $eventType->typical_seating;
        $this->duration_type = $eventType->duration_type;
        $this->description = $eventType->description;
        $this->min_duration = $eventType->min_duration;
        $this->max_duration = $eventType->max_duration;
        $this->selectedSeasons = explode(', ', $eventType->seasons);
        $this->min_people = $eventType->min_people;
        $this->max_people = $eventType->max_people;
        $this->opening_time = $eventType->opening_time;
        $this->closing_time = $eventType->closing_time;
        $this->eventTypeId = $eventType->id;
        $this->venue_area_id = $eventType->venue_area_id;
        $this->min_buffer_before = $eventType->min_buffer_before;
        $this->max_buffer_before = $eventType->max_buffer_before;
        $this->min_buffer_after = $eventType->min_buffer_after;
        $this->max_buffer_after = $eventType->max_buffer_after;
        $this->dispatchBrowserEvent('event-type-range-updated', 
        ['openingTime' => $this->opening_time, 'closingTime' => $this->closing_time]);
    }

    public function updateEventTypeRange($range) {
        $this->opening_time = $range[0];
        $this->closing_time = $range[1];
        $this->dispatchBrowserEvent('event-type-range-updated', 
        ['openingTime' => $this->opening_time, 'closingTime' => $this->closing_time]);
    }
}
