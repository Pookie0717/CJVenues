<?php

namespace App\Http\Livewire\Venue;

use Livewire\Component;
use App\Models\Venue;

class AddVenueModal extends Component
{
    public $name;
    public $type;
    public $address;

    public function submit()
    {
        // Validate the data
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Save the new venue to the database
        Venue::create([
            'name' => $this->name,
            'type' => $this->type,
            'address' => $this->address,
        ]);

        // Emit an event to notify that the venue was created successfully
        $this->emit('success');

        // Reset the form fields
        $this->reset(['name', 'type', 'address']);
    }

    public function render()
    {
        return view('livewire.venue.add-venue-modal');
    }
}
