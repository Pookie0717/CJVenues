<?php

namespace App\Http\Livewire\Tenant;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\EventType;
use App\Models\Option;
use App\Models\Price;
use App\Models\Quote;
use App\Models\Season;
use App\Models\User;
use App\Models\Venue;
use App\Models\VenueArea;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PragmaRX\Countries\Package\Countries;

class TenantModal extends Component
{
    public $name;
    public $address;
    public $city;
    public $postcode;
    public $stateprovince;
    public $country;
    public $currency;
    public $vatnumber;
    public $parent_id;
    public $edit_mode = false;
    public $tenantId; // To store the ID of the selected tenant for editing
    public $selectedCountry;
    public $selectedState;
    protected $countries;
    public $states = [];
    public $cities = [];

    public function getCountriesProperty()
    {
        return $this->countries ?? [];
    }

    public function mount()
    {
        $countries = new Countries();
        $this->countries = $countries->all()->pluck('name.common', 'cca3')->toArray();
        asort($this->countries);
        $this->states = [];
        $this->cities = [];
    }


    protected $listeners = [
        'delete_tenant' => 'deleteTenant',
        'update_tenant' => 'updateTenant',
    ];
    

    public function submit()
{
    // Validate the data
    $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:10',
            'stateprovince' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'vatnumber' => 'nullable|string|max:20',
        ]);

    // Get the currently authenticated user
    $user = Auth::user();

    if ($this->edit_mode) {
        // If in edit mode, update the existing tenant record
        $tenant = Tenant::find($this->tenantId);

        // Ensure the user has a relationship with this tenant
        if ($user->tenants->contains($tenant)) {
            $tenant->update([
                    'name' => $this->name,
                    'address' => $this->address,
                    'city' => $this->city,
                    'postcode' => $this->postcode,
                    'stateprovince' => $this->selectedState,
                    'country' => $this->selectedCountry,
                    'currency' => $this->currency,
                    'parent_id' => (int)$this->parent_id ,
                    'vatnumber' => $this->vatnumber,
                ]);

            // Emit an event to notify that the tenant was updated successfully
            $this->emit('success', 'Tenant successfully updated');
        } else {
            // Handle the case where the user does not have access to edit this tenant
            // You can show an error message or perform some other action
        }
    } else {
        // If not in edit mode, create a new tenant record
        $tenant = Tenant::where('name', $this->name)->first();

        // Check if the tenant already exists
        if (!$tenant) {
            $tenant = Tenant::create([
                    'name' => $this->name,
                    'address' => $this->address,
                    'city' => $this->city,
                    'postcode' => $this->postcode,
                    'stateprovince' => $this->selectedState,
                    'country' => $this->selectedCountry,
                    'currency' => $this->currency,
                    'parent_id' => $this->parent_id,
                    'vatnumber' => $this->vatnumber,
                ]);
            // Attach the new tenant to the currently authenticated user
            $user->tenants()->attach($tenant->id);

            // Attach the new tenant to all users with email addresses ending in "@cocoandjay.com" except the currently authenticated user
            $usersWithMatchingEmails = User::where('email', 'LIKE', '%@cocoandjay.com')
                ->where('id', '!=', $user->id) // Exclude the current user
                ->get();

            foreach ($usersWithMatchingEmails as $userWithMatchingEmail) {
                $userWithMatchingEmail->tenants()->attach($tenant->id);
            }

            $season = Season::create([
                'name' => 'All',
                'date_from' => '01-01-0000',
                'date_to' => '31-12-9999',
                'priority' => '0',
                'tenant_id' => $tenant->id,
            ]);
            // Emit an event to notify that the tenant was created successfully
            $this->emit('success', 'Tenant successfully added');
        } else {
            // Notify the user that the tenant already exists
            $this->emit('error', 'Tenant already exists');
        }
    }

    // Reset the form fields and exit edit mode
        $this->reset(['name', 'address', 'city', 'postcode', 'stateprovince', 'country', 'currency', 'vatnumber', 'edit_mode', 'parent_id']);
}

    public function deleteTenant($id)
    {
        // Find the tenant by ID
        $tenant = Tenant::find($id);

        // Retrieve all users associated with the tenant
        $allUsers = $tenant->users;

        // Check if the tenant has only one user (the currently authenticated user)
        if ($allUsers->count() === 1 && $allUsers->first()->id === Auth::user()->id) {
            // Detach the user and proceed with deletion
            Auth::user()->tenants()->detach($tenant->id);
            $this->deleteTenantRecords($tenant);
        } else {
            // Check if all users have emails ending with "@cocoandjay.com"
            $allUsersHaveMatchingEmails = $allUsers->every(function ($user) {
                return Str::endsWith($user->email, '@cocoandjay.com');
            });

            if ($allUsersHaveMatchingEmails) {
                // Detach all users and proceed with deletion
                foreach ($allUsers as $user) {
                    $user->tenants()->detach($tenant->id);
                }
                $this->deleteTenantRecords($tenant);
            } else {
                // Throw an error if there are users with non-matching emails
                $this->emit('error', 'Cannot delete organization. Some users have non-matching email addresses.');
            }
        }
    }

    private function deleteTenantRecords($tenant)
    {
        // Delete the "All" season if it exists
        Season::where('tenant_id', $tenant->id)
            ->where('name', 'All')
            ->delete();

        // Check if the tenant can be deleted
        if (
            Season::where('tenant_id', $tenant->id)->exists() ||
            Contact::where('tenant_id', $tenant->id)->exists() ||
            EventType::where('tenant_id', $tenant->id)->exists() ||
            Option::where('tenant_id', $tenant->id)->exists() ||
            Price::where('tenant_id', $tenant->id)->exists() ||
            Quote::where('tenant_id', $tenant->id)->exists() ||
            Venue::where('tenant_id', $tenant->id)->exists() ||
            VenueArea::where('tenant_id', $tenant->id)->exists()
        ) {
            $this->emit('error', 'Cannot delete organization. Records are associated with it.');
        } else {
            $tenant->delete();
            $this->emit('success', 'Organization successfully deleted');
        }
    }


    public function updateTenant($id)
    {
        $this->edit_mode = true;

        $tenant = Tenant::find($id);

        $countries = new Countries();
        $this->countries = $countries->all()->pluck('name.common', 'cca3')->toArray();
        asort($this->countries);
        $this->states = [];
        $this->cities = [];

        // Ensure the user has a relationship with this tenant
        if (Auth::user()->tenants->contains($tenant)) {
            $this->tenantId = $id;
            $this->name = $tenant->name;
            $this->address = $tenant->address;
            $this->city = $tenant->city;
            $this->postcode = $tenant->postcode;
            $this->currency = $tenant->currency;
            $this->vatnumber = $tenant->vatnumber;
            $this->selectedCountry = $tenant->country;
            $this->parent_id = $tenant->parent_id;

            $this->updatedSelectedCountry($tenant->country);

            $this->selectedState = $tenant->stateprovince;
        } else {
            $this->emit('error', 'You do not have access to edit this Organization');
        }
    }


    public function updatedSelectedCountry($countryCode)
    {
        $countries = new Countries();
        $country = $countries->where('cca3', $countryCode)->first();
        if($country) {
            $this->states = sizeof($country) > 0 ? $country->hydrate('states')->states->pluck('name', 'postal')->toArray(): [];
        }
        $this->countries = $countries->all()->pluck('name.common', 'cca3')->toArray();
        asort($this->countries);
        // $this->selectedState = null;
        // $this->selectedCountry = $countryCode;
    }


    public function updatedSelectedState($stateCode)
    {
        $countries = new Countries();
        $this->selectedState = $stateCode;
        $this->countries = $countries->all()->pluck('name.common', 'cca3')->toArray();
        asort($this->countries);
    }

    public function render()
    {
        $rootTenants= Tenant::where('parent_id', 0)->get();
        return view('livewire.tenant.tenant-modal', compact('rootTenants'));
    }
}
