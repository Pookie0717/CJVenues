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
    public $overwrite_weekday;
    public $tenant_id;

    public $edit_mode = false;

    protected $listeners = [
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
        ]);

        if ($this->edit_mode) {
            // If in edit mode, update the existing season record
            $season = Season::find($this->seasonId);
            $season->update([
                'name' => $this->name,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'priority' => $this->priority,
                'overwrite_weekday' => $this->overwrite_weekday,
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
                'overwrite_weekday' => $this->overwrite_weekday,
                'tenant_id' => $currentTenantId,
            ]);

            // Emit an event to notify that the season was created successfully
            $this->emit('success', 'Season successfully added');
        }

        // Reset the form fields and exit edit mode
        $this->reset(['name', 'date_from', 'date_to', 'priority', 'overwrite_weekday', 'edit_mode']);
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
        $this->overwrite_weekday = $season->overwrite_weekday;
        $this->tenant_id = $season->tenant_id;
    }

    public function render()
    {
        return view('livewire.season.add-season-modal');
    }
}
