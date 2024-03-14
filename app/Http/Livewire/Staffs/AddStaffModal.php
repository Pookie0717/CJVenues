<?php

namespace App\Http\Livewire\staffs;

use Livewire\Component;
use App\Models\Staffs;
use App\Models\VenueArea;
use App\Models\Venue;
use App\Models\Season;
use App\Models\Option;
use App\Models\Tenant;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AddStaffModal extends Component
{
    public $type;
    public $name;
    public $venue_ids;
    public $area_ids;
    public $tenant_id;
    public $venueAreas;
    public $edit_mode = false;
    public $staff;
    public $area_id_string;
    public $from;
    public $from_arr_string;
    public $to;
    public $to_arr_string;
    public $count;
    public $count_arr_string;
    public $duration_type;
    public $duration_type_string;
    public $items_count = 1;
    public $option = [];
    public $option_value;
    public $option_value_arr_string;
    public $isOption = false;

    protected $listeners = [
        'create_staff' => 'createStaff',
        'delete_staff' => 'deleteStaff',
        'update_staff' => 'updateStaff',
    ];

    public function submit()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|max:255',
            'area_ids' => 'required|nullable|array',
            'from' => 'required|array',
            'to' => 'required|array',
            'count' => 'required|array',
            'option' => 'nullable',
            'option_value' => 'array|nullable'
        ];

        $this->validate($rules);
        for($i = 1;$i <= $this->items_count;$i++) {
            if (!isset($this->duration_type[$i])) {
                $this->duration_type[$this->items_count] = 'hour';
            }
            $this->from[$i] = isset($this->from[$i]) ? $this->from[$i] : 0;
            $this->to[$i] = isset($this->to[$i]) ? $this->to[$i] : 0;
            $this->count[$i] = isset($this->count[$i]) ? $this->count[$i] : 0;
        }
        
        $this->area_id_string = implode(',', $this->area_ids);
        $this->from_arr_string = implode(',', $this->from);
        $this->to_arr_string = implode(',', $this->to);
        $this->count_arr_string = implode(',', $this->count);
        $this->duration_type_string = implode(',', $this->duration_type);
        $this->option_value_arr_string = $this->isOption == 'true' ? implode(',', $this->option_value) : null;

        if ($this->edit_mode) {
            // If in edit mode, update the existing staff record
            $staff = Staffs::find($this->staffId);
            $staff->update([
                'name' => $this->name,
                'type' => $this->type,
                'area_ids' => $this->area_id_string,
                'tenant_id' => $this->tenant_id,
                'from' => $this->from_arr_string ? $this->from_arr_string : null,
                'to' => $this->to_arr_string ? $this->to_arr_string : null,
                'count' => $this->count_arr_string ? $this->count_arr_string : null,
                'duration_type' => $this->duration_type_string,
                'options' => $this->option && $this->isOption == 'true' ? $this->option : null,
                'option_values' => $this->option_value_arr_string && $this->option ? $this->option_value_arr_string : null
            ]);

            // Emit an event to notify that the price was updated successfully
            $this->emit('success', 'Staff successfully updated');
        } else {
            // If not in edit mode, create a new price record
            Staffs::create([
                'name' => $this->name,
                'type' => $this->type,
                'area_ids' => $this->area_id_string,
                'tenant_id' => $this->tenant_id,
                'from' => $this->from_arr_string ? $this->from_arr_string : null,
                'to' => $this->to_arr_string ? $this->to_arr_string : null,
                'count' => $this->count_arr_string ? $this->count_arr_string : null,
                'duration_type' => $this->duration_type_string,
                'options' => $this->option && $this->isOption == 'true' ? $this->option : null,
                'option_values' => $this->option_value_arr_string && $this->option ? $this->option_value_arr_string : null
            ]);
            // Emit an event to notify that the price was created successfully
            $this->emit('success', 'Staff successfully added');
        }

        // Reset the form fields and exit edit mode
        $this->reset([
            'name', 'type', 'venue_ids', 'area_ids', 'from', 'to', 'count', 'duration_type', 'items_count', 'option', 'option_value', 'isOption'
        ]);
    }

    public function createStaff() {
        $this->edit_mode = false;
        $this->tenant_id = Session::get('current_tenant_id');
        $this->reset([
            'name', 'type', 'venue_ids', 'area_ids', 'from', 'to', 'count', 'duration_type', 'items_count', 'option', 'option_value', 'isOption'
        ]);
    }

    public function deleteStaff($id)
    {
        // Find the staff by ID
        $staff = Staffs::find($id);

        // Delete the price
        $staff->delete();

        // Emit a success event with a message
        $this->emit('success', 'Staff successfully deleted');
    }

    public function updateStaff($id)
    {
        $this->edit_mode = true;
        $staff = Staffs::find($id);
        $this->type = $staff->type;
        $this->staffId = $id;
        $this->items_count = count(explode(',', $staff->duration_type));
        $this->tenant_id = $staff->tenant_id;
        $this->name = $staff->name;
        $this->area_ids= explode(',', $staff->area_ids);
        $from = explode(',', $staff->from);
        $to = explode(',', $staff->to);
        $count = explode(',', $staff->count);
        $duration_type = explode(',', $staff->duration_type);
        for($i = 1;$i <= $this->items_count;$i++) {
            $j = $i - 1;
            $this->from[$i] = $from[$j];
            $this->to[$i] = $to[$j];
            $this->count[$i] = $count[$j];
            $this->duration_type[$i] = $duration_type[$j];
        }
        $this->option = $staff->options;
        $this->option_value = explode(',', $staff->option_values);
        $this->isOption = $this->option ? 'true' : 'false';
    }

    public function addItem() {
        $this->duration_type[$this->items_count] = 'hour';
        $this->items_count += 1;
    }

    public function removeItem($index) {
        if($this->items_count > 1) {
            $this->items_count -= 1;
        }
        unset($this->from[$index]);
        unset($this->to[$index]);
        unset($this->count[$index]);
        unset($this->duration_type[$index]);
    }

    public function render()
    {
        Log::info($this->option);
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = [];
        $venueArea = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $parentTanantId = Tenant::where('id', $currentTenantId)->pluck('parent_id')->toArray();
        $tenantIds[] = $currentTenantId;
        $venues = Venue::whereIn('tenant_id', $tenantIds)->get();
        $options_arr = Option::whereIn('tenant_id', $tenantIds)->get();
        $option_value_arr = [];
        if((int) $this->option > 0) {
            $selected_option_arr = Option::where('id', (int) $this->option)->get();
            $option_value_arr = explode('|', $selected_option_arr[0]['values']);
        }
        if($parentTanantId[0] !== null) {
            $venueAreaChild = VenueArea::whereIn('tenant_id', $tenantIds)->get()->toArray();
            $venueAreaParent = VenueArea::whereIn('tenant_id', $parentTanantId)->get()->toArray();
            if(count($venueAreaParent) !== 0) {
                if(count($venueAreaChild) !== 0) {
                    $venueArea = array_merge($venueAreaParent, $venueAreaChild);
                } else {
                    $venueArea = $venueAreaParent;
                }
            } else {
                $venueArea = $venueAreaChild;
            }
        } else {
            $venueArea = VenueArea::whereIn('tenant_id', $tenantIds)->get();
        }

        return view('livewire.staffs.add-staff-modal', compact('venues', 'venueArea', 'parentTanantId', 'options_arr', 'option_value_arr'));
    }
}
