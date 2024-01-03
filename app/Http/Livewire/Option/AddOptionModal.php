<?php

namespace App\Http\Livewire\Option;

use Livewire\Component;
use App\Models\Option;
use App\Models\Season;
use App\Models\Venue;
use App\Models\Tenant;
use App\Models\VenueArea;
use App\Models\EventType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AddOptionModal extends Component
{
    public $tenant_id;
    public $name;
    public $position;
    public $type;
    public $values;
    public $venue_id;
    public $season_ids = [];
    public $venue_ids = [];
    public $area_ids = [];
    public $eventtype_ids = [];
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
        'type' => 'required|in:yes_no,always,check,radio,number,dropdown,logic',
        'values' => 'nullable|string',
        'season_ids' => 'nullable|array',
        'season_ids.*' => 'exists:seasons,id',
        'venue_ids' => 'nullable|array',
        'venue_ids.*' => 'exists:venues,id',
        'area_ids' => 'nullable|array',
        'area_ids.*' => 'exists:venue_areas,id',
        'eventtype_ids' => 'nullable|array',
        'eventtype_ids.*' => 'exists:event_types,id',
        'description' => 'nullable|string|max:255',
        'default_value' => 'nullable|string|max:255',
        'vat' => 'nullable|numeric',
    ];

    protected $listeners = [
        'delete_option' => 'deleteOption',
        'update_option' => 'updateOption',
        'create_option' => 'createOption'
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

            // Convert area_ids to an array if it's not already
            if (!is_array($this->area_ids)) {
                $this->area_ids = [];
            }

            // Convert eventtype_ids to an array if it's not already
            if (!is_array($this->eventtype_ids)) {
                $this->eventtype_ids = [];
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
        $this->tenant_id = $option->tenant_id;
        $this->season_ids = explode(',', $option->season_ids);
        $this->venue_ids = explode(',', $option->venue_ids);
        $this->area_ids = explode(',', $option->area_ids);
        $this->eventtype_ids = explode(',', $option->eventtype_ids);
        $this->optionId = $id;
        $this->type = $option->type;
        if($this->type === "logic") {
            $this->conditions = $this->generateLogic($option->logic);
        }
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

    public function createOption() {
        $this->edit_mode = false;
        $this->tenant_id = Session::get('current_tenant_id');
        $this->resetFields();
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
            'area_ids',
            'eventtype_ids',
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
        $areaIds = implode(',', $this->area_ids);
        $eventTypeIds = implode(',', $this->eventtype_ids);

        return [
            'tenant_id' => $this->tenant_id,
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
            'area_ids' => $areaIds,   // Updated to comma-separated string
            'eventtype_ids' => $eventTypeIds,   // Updated to comma-separated string
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

    protected function generateLogic($logicExpression) {

        $conditions = [];
        $orConditions = explode(' OR ', $logicExpression);
        foreach ($orConditions as $orCondition) {
            $andConditions = explode(' AND ', $orCondition);
            foreach ($andConditions as $index => $andCondition) {
                $arr = explode(" ", $andCondition);
                $conditions[] = [
                    'logical_operator' => $index === 0 ? 'OR' : 'AND',
                    'field' => $arr[0],
                    'operator' => $arr[1],
                    'value' => preg_replace('/[^0-9]/', '', $arr[2]),
                ];
            }
        }

        return $conditions;
    }

    public function render()
    {
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;
        
        $seasons = Season::whereIn('tenant_id', $tenantIds)->get();
        $venues = Venue::whereIn('tenant_id', $tenantIds)->get();
        $areas = VenueArea::whereIn('tenant_id', $tenantIds)->get();
        $eventTypes = EventType::whereIn('tenant_id', $tenantIds)->get();

        return view('livewire.option.add-option-modal', compact('seasons', 'venues', 'areas', 'eventTypes'));
    }
}
