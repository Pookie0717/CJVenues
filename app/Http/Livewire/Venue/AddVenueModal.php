<?php

namespace App\Http\Livewire\Venue;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Venue;
use App\Models\VenueArea;


class AddVenueModal extends Component
{
    public $venueName;
    public $venueType;
    public $venueAddress;
    public $venueId;
    public $city;
    public $postcode;
    public $state;
    public $country;
    public $notes;
    public $showAreasModal = false; // Flag to control showing the areas modal
    public $areas = []; // Array to store venue areas

    public $edit_mode = false;


    protected $rules = [
        'venueName' => 'required|string|max:255',
        'venueType' => 'required|string|max:255',
        'venueAddress' => 'required|string|max:255',
        'areas.*.name' => 'required|string|max:255',
        'areas.*.capacity_noseating' => 'required|integer|min:0',
        'areas.*.capacity_seatingrows' => 'required|integer|min:0',
        'areas.*.capacity_seatingtables' => 'required|integer|min:0',
    ];

    protected $listeners = [
        'create_venue' => 'createVenue',
        'delete_venue' => 'deleteVenue',
        'update_venue' => 'updateVenue',
        'check_areas' => 'checkAreasAssociation',
    ];

    public function render()
    {
        return view('livewire.venue.add-venue-modal');
    }

    public function showAreasModal()
    {
        $this->validate();
        $this->showAreasModal = true;
    }

    public function hideAreasModal()
    {
        $this->showAreasModal = false;
        // Clear the areas array when closing the modal
        $this->areas = [];
    }

    public function addArea()
    {
        // Validate and add an area to the areas array
        $this->validate([
            'areas.*.name' => 'required|string|max:255',
            'areas.*.capacity_noseating' => 'required|integer|min:0',
            'areas.*.capacity_seatingrows' => 'required|integer|min:0',
            'areas.*.capacity_seatingtables' => 'required|integer|min:0',
        ]);

        $this->areas[] = [
            'name' => '',
            'capacity_noseating' => 0,
            'capacity_seatingrows' => 0,
            'capacity_seatingtables' => 0,
        ];
    }

    public function removeArea($index)
    {
        // Remove an area from the areas array
        unset($this->areas[$index]);
        // Reset array keys
        $this->areas = array_values($this->areas);
    }

    public function submit()
    {
        // Validate venue data and areas
        $this->validate();

        if ($this->edit_mode) {

            \Log::info('Venue ID before find:', [$this->venueId]);
            $venue = Venue::find($this->venueId);
            \Log::info('Resulting Venue:', [$venue]);

            $venue->update([
                'name' => $this->venueName,
                'type' => $this->venueType,
                'address' => $this->venueAddress,
            ]);

            $this->emit('success', 'Venue successfully updated');

        } else {

            // Save venue to the database
            $venue = Venue::create([
                'name' => $this->venueName,
                'type' => $this->venueType,
                'address' => $this->venueAddress,
            ]);

            // Save associated areas if available
            if (!empty($this->areas)) {
                foreach ($this->areas as $area) {
                    $venue->areas()->create([
                        'name' => $area['name'],
                        'capacity_noseating' => $area['capacity_noseating'],
                        'capacity_seatingrows' => $area['capacity_seatingrows'],
                        'capacity_seatingtables' => $area['capacity_seatingtables'],
                    ]);
                }
            }

            // Reset form fields and areas array
            $this->resetFields();
            $this->resetAreas();

            // Close the modal (you can emit an event to handle this in your JavaScript)
            $this->emit('success', 'Venue successfully Added');

            // Display success message (you can emit an event to display this in your JavaScript)
            $this->emit('showSuccessMessage', 'Venue and venue areas added successfully!');

        }
    }

    public function createVenue() {
        $this->edit_mode = false;
        $this->resetFields();
        $this->resetAreas();
    }

    public function deleteVenue($id)
    {
        // Find the venue by ID
        $venue = Venue::find($id);

        // Check if there are any associated areas
        if ($venue->areas->count() > 0) {
            // If there are associated areas, prevent deletion and provide an error message
            $this->emit('showErrorMessage', 'Venue cannot be deleted because it has associated areas.');
            return;
        }

        // If no associated areas, proceed with deletion
        $venue->delete();

        // Emit a success event with a message
        $this->emit('success', 'Area successfully deleted');
    }

    public function updateVenue($id)
    {
        $this->edit_mode = true;

        $venue = Venue::find($id);
        $this->venueId = $id;;
        $this->venueName  = $venue->name;
        $this->venueType  = $venue->type;
        $this->venueAddress  = $venue->address;

    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function resetFields()
    {
        $this->venueName = '';
        $this->venueType = '';
        $this->venueAddress = '';
    }

    private function resetAreas()
    {
        $this->areas = [];
    }
}
