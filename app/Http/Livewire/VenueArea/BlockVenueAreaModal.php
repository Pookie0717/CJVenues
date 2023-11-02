<?php

namespace App\Http\Livewire\VenueArea;

use Livewire\Component;
use App\Models\VenueArea;
use App\Models\BlockedArea;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class BlockVenueAreaModal extends Component
{
    public $start_date;
    public $end_date;
    public $area_id;

    protected $listeners = [
        'block_area' => 'blockArea',
    ];

    public function blockArea($areaId)
    {
        $this->area_id = $areaId;
        $this->start_date = null;
        $this->end_date = null;
    }

    public function submit()
    {
        // Validate the form input
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Create a new BlockedArea record
        BlockedArea::create([
            'area_id' => $this->area_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        // Optionally, you can add a success message or perform other actions here

        // Close the modal
        $this->emit('closeModal'); // Add this event in your JavaScript

        // Clear form input
        $this->reset();
    }

    public function render()
    {
        return view('livewire.venue-area.block-venue-area-modal');
    }
}
