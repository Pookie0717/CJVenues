<?php

namespace App\Http\Livewire\Option;

use Livewire\Component;
use App\Models\Option;
use App\Models\Season;
use App\Models\Venue;

class AddOptionModal extends Component
{
    public $name;
    public $position;
    public $type;
    public $values;
    public $venue_id;
    public $season_id;

    public $edit_mode = false;
    public $optionId;

    protected $listeners = [
        'delete_option' => 'deleteOption',
        'update_option' => 'updateOption',
    ];

    public function submit()
    {
        // Validate the data
        $this->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|integer',
            'type' => 'required|in:yes_no,check,radio,number,dropdown',
            'values' => 'nullable|string', // You may need to adjust this validation based on the format of the values
        ]);

        if ($this->edit_mode) {
            // If in edit mode, update the existing option record
            $option = Option::find($this->optionId);
            $option->update([
                'name' => $this->name,
                'position' => $this->position,
                'type' => $this->type,
                'values' => $this->values,
                'venue_id' => $this->venue_id,
                'season_id' => $this->season_id,
            ]);

            // Emit an event to notify that the option was updated successfully
            $this->emit('success', 'Option successfully updated');
        } else {
            // Save the new option to the database
            Option::create([
                'name' => $this->name,
                'position' => $this->position,
                'type' => $this->type,
                'values' => $this->values,
                'venue_id' => $this->venue_id,
                'season_id' => $this->season_id,
            ]);

            // Emit an event to notify that the option was created successfully
            $this->emit('success', 'Option successfully added');
        }

        // Reset the form fields
        $this->reset(['name', 'position', 'type', 'values', 'edit_mode', 'optionId', 'season_id', 'venue_id']);
    }

    public function deleteOption($id)
    {
        // Find the option by ID
        $option = Option::find($id);

        // Delete the option
        $option->delete();

        // Emit a success event with a message
        $this->emit('success', 'Option successfully deleted');
    }

    public function updateOption($id)
    {
        $this->edit_mode = true;
        $option = Option::find($id);

        $this->name = $option->name;
        $this->position = $option->position;
        $this->type = $option->type;
        $this->values = $option->values;
        $this->optionId = $option->id;
        $this->venue_id = $option->venue_id;
        $this->season_id = $option->season_id;
    }

    public function render()
    {
        $seasons = Season::all();
        $venues = Venue::all();
        return view('livewire.option.add-option-modal', compact('seasons', 'venues'));
    }
}

