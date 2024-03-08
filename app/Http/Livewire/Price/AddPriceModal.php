<?php

namespace App\Http\Livewire\Price;

use Livewire\Component;
use App\Models\Price;
use App\Models\VenueArea;
use App\Models\Venue;
use App\Models\Season;
use App\Models\Option;
use App\Models\Tenant;
use App\Models\Staffs;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AddPriceModal extends Component
{
    public $tenant_id;

    public $name;
    public $priority;
    public $overwrite_weekday;
    public $type;
    public $venue_id;
    public $area_id;
    public $staff_id;
    public $option_area_ids;
    public $option_id;
    public $price;
    public $multiplier;
    public $priceId;
    public $season_ids;
    public $tier_type;
    public $extra_tier_type = [];
    public $x = 1;

    public $edit_mode = false;

    protected $listeners = [
        'create_price' => 'createPrice',
        'delete_price' => 'deletePrice',
        'update_price' => 'updatePrice',
    ];

    public function submit()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required',
            'venue_id' => 'nullable|integer',
            'area_id' => 'nullable|integer',
            'staff_id' => 'nullable|integer',
            'option_id' => 'nullable|integer',
            'price' => 'required|string',
            'multiplier' => 'nullable|string|max:255',
            'x' => 'required|integer|min:1',
            'season_ids' => 'required|array',
            'season_ids.*' => 'exists:seasons,id',
            'extra_tier_type' => 'array',
            'extra_tier_type.*' => 'in:buffer_before,buffer_after,event',
        ];

        $this->validate($rules);

        $extraTierTypeString = implode(',', $this->extra_tier_type);

        if ($this->edit_mode) {
            // If in edit mode, update the existing price record
            $price = Price::find($this->priceId);
            $price->update([
                'tenant_id' => $this->tenant_id,
                'name' => $this->name,
                'type' => $this->type,
                'venue_id' => ($this->type === 'venue') ? $this->venue_id : null,
                'area_id' => ($this->type === 'area') ? $this->area_id : null,
                'option_area_ids' => ($this->type === 'option') ? implode(',', $this->option_area_ids) : null,
                'staff_id' => ($this->type === 'staff') ? $this->staff_id : null,
                'option_id' => ($this->type === 'option') ? $this->option_id : null,
                'tier_type' => ($this->type === 'pp_tier') ? $this->tier_type : null,
                'price' => $this->price,
                'multiplier' => $this->multiplier,
                'x' => $this->x,
                'season_ids' => implode(',', $this->season_ids),
                'extra_tier_type' => $extraTierTypeString,
            ]);

            // Emit an event to notify that the price was updated successfully
            $this->emit('success', 'Price successfully updated');
        } else {
            // If not in edit mode, create a new price record

            // foreach($this->season_ids as $seasonId) {
                Log::info(implode(',', $this->season_ids));
                Price::create([
                    'tenant_id' => $this->tenant_id,
                    'name' => $this->name,
                    'type' => $this->type,
                    'venue_id' => ($this->type === 'venue') ? $this->venue_id : null,
                    'area_id' => ($this->type === 'area') ? $this->area_id : null,
                    'option_area_ids' => ($this->type === 'option') ? implode(',', $this->option_area_ids) : null,
                    'staff_id' => $this->type === 'staff' ? $this->staff_id : null,
                    'option_id' => ($this->type === 'option') ? $this->option_id : null,
                    'tier_type' => ($this->type === 'pp_tier') ? $this->tier_type : null,
                    'price' => $this->price,
                    'multiplier' => $this->multiplier,
                    'x' => $this->x,
                    'season_ids' => implode(',', $this->season_ids),
                    'extra_tier_type' => $extraTierTypeString,
                ]);
            // }

            // Emit an event to notify that the price was created successfully
            $this->emit('success', 'Price successfully added');
        }

        // Reset the form fields and exit edit mode
        $this->reset([
            'name', 'type', 'venue_id', 'area_id', 'staff_id', 'option_id', 'tier_type', 'price', 'multiplier', 'x', 'edit_mode', 'option_area_ids', 'season_ids', 'extra_tier_type'
        ]);
    }

    public function createPrice() {
        $this->edit_mode = false;
        $this->tenant_id = Session::get('current_tenant_id');
        $this->reset([
            'name', 'type', 'venue_id', 'area_id','staff_id', 'option_id', 'tier_type', 'price', 'multiplier', 'x', 'edit_mode', 'option_area_ids', 'season_ids', 'extra_tier_type'
        ]);
    }

    public function deletePrice($id)
    {
        // Find the price by ID
        $price = Price::find($id);

        // Delete the price
        $price->delete();

        // Emit a success event with a message
        $this->emit('success', 'Price successfully deleted');
    }

    public function updatePrice($id)
    {
        $this->edit_mode = true;

        $price = Price::find($id);

        $this->priceId = $id;
        $this->tenant_id = $price->tenant_id;

        $this->name = $price->name;
        $this->priority = $price->priority;
        $this->overwrite_weekday = $price->overwrite_weekday;
        $this->type = $price->type;
        $this->venue_id = $price->venue_id;
        $this->area_id = $price->area_id;
        $this->staff_id = $price->staff_id;
        $this->option_id = $price->option_id;
        $this->tier_type = $price->tier_type;
        $this->price = $price->price;
        $this->multiplier = $price->multiplier;
        $this->x = $price->x;
        $this->season_ids = explode(',', $price->season_ids);
        $this->extra_tier_type = explode(',', $price->extra_tier_type);
        $this->option_area_ids = explode(',', $price->option_area_ids);
    }


    public function render()
    {
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;

        $venues = Venue::whereIn('tenant_id', $tenantIds)->get();
        $venueAreas = VenueArea::whereIn('tenant_id', $tenantIds)->get();
        $seasons = Season::whereIn('tenant_id', $tenantIds)->get();
        $options = Option::whereIn('tenant_id', $tenantIds)->get();
        $staffs = Staffs::whereIn('tenant_id', $tenantIds)->get();

        $dX = stristr($this->multiplier, 'every');

        $optionAreas = [];
        $selectedOption = Option::find($this->option_id);
                if($this->type === 'option' && $selectedOption) {
            $areaIds = explode(',', $selectedOption->area_ids);
            $optionAreas = VenueArea::whereIn('id', $areaIds)->get();
        }
        return view('livewire.price.add-price-modal', compact('venues', 'venueAreas', 'seasons', 'options', 'dX', 'optionAreas', 'staffs'));
    }
}
