<?php

namespace App\Http\Livewire\VenueArea;

use Livewire\Component;
use App\Models\VenueArea;
use App\Models\Venue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

Validator::extend('at_least_one_capacity', function ($attribute, $value, $parameters, $validator) {
    $data = $validator->getData();
    return isset($data['capacity_noseating']) || isset($data['capacity_seatingrows']) || isset($data['capacity_seatingtables']);
});

class AddVenueAreaModal extends Component
{
    public $tenant_id;
    public $venue_id;
    public $name;
    public $capacity_noseating;
    public $capacity_seatingrows;
    public $capacity_seatingtables;

    public $edit_mode = false;

    protected $listeners = [
        'create_area' => 'createArea',
        'delete_area' => 'deleteArea',
        'update_area' => 'updateArea',
    ];

    public function submit()
    {
        // Validate the data
        $this->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'capacity_noseating' => 'integer|nullable',
            'capacity_seatingrows' => 'integer|nullable',
            'capacity_seatingtables' => 'integer|nullable',
            // Apply the custom rule to any of the capacity fields
            'capacity_noseating' => 'at_least_one_capacity',
        ], [
            'at_least_one_capacity' => 'At least one of the capacity fields must be filled.',
        ]);


        if ($this->edit_mode) {
            // If in edit mode, update the existing season record
            $area = VenueArea::find($this->areaId);
            $area->update([
                'tenant_id' => $this->tenant_id,
                'venue_id' => $this->venue_id,
                'name' => $this->name,
                'capacity_noseating' => $this->capacity_noseating,
                'capacity_seatingrows' => $this->capacity_seatingrows,
                'capacity_seatingtables' => $this->capacity_seatingtables,
            ]);

            // Emit an event to notify that the season was updated successfully
            $this->emit('success', 'Area successfully updated');
        } else {
           
             // Save the new venue area to the database
            VenueArea::create([
                'tenant_id' => $this->tenant_id,
                'venue_id' => $this->venue_id,
                'name' => $this->name,
                'capacity_noseating' => $this->capacity_noseating,
                'capacity_seatingrows' => $this->capacity_seatingrows,
                'capacity_seatingtables' => $this->capacity_seatingtables,
            ]);
            // Emit an event to notify that the venue area was created successfully
            $this->emit('success', 'Area successfully added');
        }

        // Reset the form fields
        $this->reset(['venue_id', 'name', 'capacity_noseating', 'capacity_seatingrows', 'capacity_seatingtables']);
    }

    public function render()
    {
        
        $currentTenantId = Session::get('current_tenant_id');
        $venues = Venue::where('tenant_id', $currentTenantId)->get();
        
        return view('livewire.venue-area.add-venue-area-modal', compact('venues'));
    }

    public function createArea() {
        $this->edit_mode = false;
        $this->tenant_id = Session::get('current_tenant_id');
        $this->reset(['venue_id', 'name', 'capacity_noseating', 'capacity_seatingrows', 'capacity_seatingtables']);
    }

    public function deleteArea($id)
    {
        // Delete the user record with the specified ID
        VenueArea::destroy($id);

        // Emit a success event with a message
        $this->emit('success', 'Area successfully deleted');
    }

    public function updateArea($id)
    {
        $this->edit_mode = true;

        $currentTenantId = Session::get('current_tenant_id');
        $venueArea = VenueArea::where('id', $id)
            ->where('tenant_id', $currentTenantId)->first();

        $this->areaId = $id;
        $this->tenant_id = $venueArea->tenant_id;

        $this->venue_id = $venueArea->venue_id;
        $this->name = $venueArea->name;
        $this->capacity_noseating = $venueArea->capacity_noseating;
        $this->capacity_seatingrows = $venueArea->capacity_seatingrows;
        $this->capacity_seatingtables = $venueArea->capacity_seatingtables;
    }
}
