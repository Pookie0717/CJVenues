<?php

namespace App\Http\Livewire\Season;

use Livewire\Component;
use App\Models\Season;
use Illuminate\Support\Facades\Session;

class AddSeasonModal extends Component
{
    public $name;
    public $date_from;
    public $date_to;
    public $priority;
    public $weekdays;
    public $tenant_id;
    public $selectedWeekdays = []; // Array to store selected weekdays

    public $edit_mode = false;

    protected $listeners = [
        'create_season' => 'createSeason',
        'delete_season' => 'deleteSeason',
        'update_season' => 'updateSeason',
    ];
    
   public function submit()
    {
        // Validate the data

        $currentTenantId = Session::get('current_tenant_id');

        $this->validate([
            'name' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'priority' => 'required|max:255',
            'selectedWeekdays' => 'required|array', // Make sure it's an array
        ]);

        // Serialize the selected weekdays array into a string
        $serializedWeekdays = json_encode($this->selectedWeekdays);

        if ($this->edit_mode) {
            // If in edit mode, update the existing season record
            $season = Season::find($this->seasonId);
            $season->update([
                'name' => $this->name,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'priority' => $this->priority,
                'weekdays' => $serializedWeekdays, // Save the serialized string
                'tenant_id' => $currentTenantId,
            ]);

            // Emit an event to notify that the season was updated successfully
            $this->emit('success', 'Season successfully updated');
        } else {
            // If not in edit mode, create a new season record
            Season::create([
                'name' => $this->name,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'priority' => $this->priority,
                'weekdays' => $serializedWeekdays, // Save the serialized string
                'tenant_id' => $currentTenantId,
            ]);

            // Emit an event to notify that the season was created successfully
            $this->emit('success', 'Season successfully added');
        }

        // Reset the form fields and exit edit mode
        $this->reset(['name', 'date_from', 'date_to', 'priority', 'selectedWeekdays', 'edit_mode']);
    }


    public function createSeason() {
        $this->edit_mode = false;
        $this->reset(['name', 'date_from', 'date_to', 'priority', 'selectedWeekdays', 'edit_mode']);
    }
    
    public function deleteSeason($id)
    {
        // Find the venue by ID
        $season = Season::find($id);

        // Check if the season being deleted is the "All" season
        if ($season->name === 'All') {
            return redirect()->back()->with('error', 'Cannot delete this season.');
        }

        // If no associated areas, proceed with deletion
        $season->delete();

        // Emit a success event with a message
        $this->emit('success', 'Season successfully deleted');
    }

    public function updateSeason($id)
    {
        $this->edit_mode = true;

        $season = Season::find($id);
        
        $this->seasonId = $id;
        $this->name = $season->name;
        $this->date_from = $season->date_from;
        $this->date_to = $season->date_to;
        $this->priority = $season->priority;
        $this->weekdays = $season->weekdays;
        $this->tenant_id = $season->tenant_id;
    }

    public function render()
    {
        return view('livewire.season.add-season-modal');
    }
}
