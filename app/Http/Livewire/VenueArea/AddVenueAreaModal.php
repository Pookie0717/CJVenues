<?php

namespace App\Http\Livewire\VenueArea;

use Livewire\Component;
use App\Models\VenueArea;
use App\Models\Venue;
use Illuminate\Support\Facades\Validator;

Validator::extend('at_least_one_capacity', function ($attribute, $value, $parameters, $validator) {
    $data = $validator->getData();
    return isset($data['capacity_noseating']) || isset($data['capacity_seatingrows']) || isset($data['capacity_seatingtables']);
});

class AddVenueAreaModal extends Component
{
    public $venue_id;
    public $name;
    public $capacity_noseating;
    public $capacity_seatingrows;
    public $capacity_seatingtables;

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


        // Save the new venue area to the database
        VenueArea::create([
            'venue_id' => $this->venue_id,
            'name' => $this->name,
            'capacity_noseating' => $this->capacity_noseating,
            'capacity_seatingrows' => $this->capacity_seatingrows,
            'capacity_seatingtables' => $this->capacity_seatingtables,
        ]);

        // Emit an event to notify that the venue area was created successfully
        $this->emit('success');

        // Reset the form fields
        $this->reset(['venue_id', 'name', 'capacity_noseating', 'capacity_seatingrows', 'capacity_seatingtables']);
    }

    public function render()
    {
        // Load venues for selection
        $venues = Venue::all();
        
        return view('livewire.venue-area.add-venue-area-modal', compact('venues'));
    }
}
