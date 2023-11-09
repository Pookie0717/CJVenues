<?php

namespace App\Http\Livewire\Option;

use Livewire\Component;
use App\Models\Option;
use App\Models\Season;
use App\Models\Venue;
use Illuminate\Support\Facades\Session;

class AddOptionModal extends Component
{
    public $name;
    public $position;
    public $type;
    public $values;
    public $venue_id;
    public $season_ids = [];
    public $venue_ids = [];
    public $description;
    public $default_value;
    public $vat;
    public $always_included = false;
    public $conditions = [];

    public $edit_mode = false;
    public $optionId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'position' => 'required|integer',
        'type' => 'required|in:yes_no,check,radio,number,dropdown,logic',
        'values' => 'nullable|string',
        'season_ids' => 'nullable|array',
        'season_ids.*' => 'exists:seasons,id',
        'venue_ids' => 'nullable|array',
        'venue_ids.*' => 'exists:venues,id',
        'description' => 'nullable|string|max:255',
        'default_value' => 'nullable|string|max:255',
        'vat' => 'nullable|numeric',
    ];

    protected $listeners = [
        'delete_option' => 'deleteOption',
        'update_option' => 'updateOption',
    ];

    public function submit()
{
    try {
        $this->validate();

        // Convert venue_ids to an array if it's not already
        if (!is_array($this->venue_ids)) {
            $this->venue_ids = [];
        }

        // Convert season_ids to an array if it's not already
        if (!is_array($this->season_ids)) {
            $this->season_ids = [];
        }

        if ($this->edit_mode) {
            $option = Option::find($this->optionId);

            if (!$option) {
                throw new \Exception('Option not found with ID: ' . $this->optionId);
            }

            $option->update($this->getUpdatedData());
            $this->emit('success', 'Option successfully updated');
        } else {
            Option::create($this->getUpdatedData());
            $this->emit('success', 'Option successfully added');
        }

        $this->resetFields();

        // Dispatch a browser event for success
        $this->dispatchBrowserEvent('optionSaved', ['message' => 'Option saved successfully']);
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error($e->getMessage());

        // Dispatch a browser event for error
        $this->dispatchBrowserEvent('optionError', ['message' => 'An error occurred']);
    }
}


    public function deleteOption($id)
    {
        $option = Option::find($id);
        $option->delete();
        $this->emit('success', 'Option successfully deleted');
    }

public function updateOption($id)
{
    $this->edit_mode = true;
    $option = Option::find($id);
    $this->fill($option->toArray());

    // Convert the comma-separated string back to an array
    $this->season_ids = explode(',', $option->season_ids);
    $this->venue_ids = explode(',', $option->venue_ids);
    $this->optionId = $id;
}



    public function addCondition()
    {
        $this->conditions[] = [
            'logical_operator' => 'AND', // Default logical operator
            'field' => null,
            'operator' => null,
            'value' => null,
        ];
    }

    public function removeCondition($index)
    {
        unset($this->conditions[$index]);
        $this->conditions = array_values($this->conditions);
    }

    protected function resetFields()
    {
        $this->reset([
            'name',
            'position',
            'type',
            'values',
            'edit_mode',
            'optionId',
            'season_ids',
            'venue_ids',
            'description',
            'default_value',
            'vat',
            'always_included',
            'conditions',
        ]);
    }

    protected function getUpdatedData()
    {
        // Convert arrays to comma-separated strings
        $seasonIds = implode(',', $this->season_ids);
        $venueIds = implode(',', $this->venue_ids);

        return [
            'name' => $this->name,
            'position' => $this->position,
            'type' => $this->type,
            'values' => $this->values,
            'description' => $this->description,
            'default_value' => $this->default_value,
            'vat' => $this->vat,
            'always_included' => $this->always_included,
            'logic' => $this->generateLogicString(),
            'season_ids' => $seasonIds, // Updated to comma-separated string
            'venue_ids' => $venueIds,   // Updated to comma-separated string
        ];
    }



    protected function generateLogicString()
    {
        $logicString = '';

        foreach ($this->conditions as $index => $condition) {
            if ($index > 0) {
                $logicString .= ' ' . strtoupper($condition['logical_operator']) . ' ';
            }

            $logicString .= $condition['field'] . ' ' . $condition['operator'] . ' "' . $condition['value'] . '"';
        }

        return $logicString;
    }

    public function render()
    {
        $currentTenantId = Session::get('current_tenant_id');
        $seasons = Season::where('tenant_id', $currentTenantId)->get();
        $venues = Venue::where('tenant_id', $currentTenantId)->get();
        return view('livewire.option.add-option-modal', compact('seasons', 'venues'));
    }
}
