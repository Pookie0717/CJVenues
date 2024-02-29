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
            'duration_type' => 'array|nullable',
            'from' => 'array|nullable',
            'to' => 'array|nullable',
            'count' => 'array|nullable'
        ];

        $this->validate($rules);
        if (!isset($this->duration_type[$this->items_count])) {
            $this->duration_type[$this->items_count] = 'hour';
        }

        $this->area_id_string = implode(',', $this->area_ids);
        $this->from_arr_string = implode(',', $this->from);
        $this->to_arr_string = implode(',', $this->to);
        $this->count_arr_string = implode(',', $this->count);
        $this->duration_type_string = implode(',', $this->duration_type);

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
                'from' => $this->from_arr_string,
                'to' => $this->to_arr_string,
                'count' => $this->count_arr_string,
                'duration_type' => $this->duration_type_string
            ]);
            // Emit an event to notify that the price was created successfully
            $this->emit('success', 'Staff successfully added');
        }

        // Reset the form fields and exit edit mode
        $this->reset([
            'name', 'type', 'venue_ids', 'area_ids', 'from', 'to', 'count', 'duration_type', 'items_count'
        ]);
    }

    public function createStaff() {
        $this->edit_mode = false;
        $this->tenant_id = Session::get('current_tenant_id');
        $this->reset([
            'name', 'type', 'venue_ids', 'area_ids', 'from', 'to', 'count', 'duration_type', 'items_count'
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
        $this->staffId = $id;
        $this->items_count = count(explode(',', $staff->duration_type));
        $this->tenant_id = $staff->tenant_id;
        $this->name = $staff->name;
        $this->type = $staff->type;
        $this->area_ids = explode(',', $staff->area_ids);
        $this->from = explode(',', $staff->from);
        $this->to = explode(',', $staff->to);
        $this->count = explode(',', $staff->count);
        $this->duration_type = explode(',', $staff->duration_type);
    }

    public function addItem() {
        if (!isset($this->duration_type[$this->items_count])) {
            $this->duration_type[$this->items_count] = 'hour';
        }
        $this->items_count += 1;
    }

    public function removeItem() {
        if (!isset($this->duration_type[$this->items_count])) {
            $this->duration_type[$this->items_count] = 'hour';
        }
        if($this->items_count > 1) {
            $this->items_count -= 1;
        }
    }

    public function render()
    {
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;

        $venues = Venue::whereIn('tenant_id', $tenantIds)->get();
        $venueArea = VenueArea::whereIn('tenant_id', $tenantIds)->get();

        return view('livewire.staffs.add-staff-modal', compact('venues', 'venueArea'));
    }
}