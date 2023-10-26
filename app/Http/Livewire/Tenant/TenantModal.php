<?php

namespace App\Http\Livewire\Tenant;

use Livewire\Component;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class TenantModal extends Component
{
    public $name;
    public $edit_mode = false;
    public $tenantId; // To store the ID of the selected tenant for editing

    protected $listeners = [
        'delete_tenant' => 'deleteTenant',
        'update_tenant' => 'updateTenant',
    ];
    

    public function submit()
{
    // Validate the data
    $this->validate([
        'name' => 'required|string|max:255',
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
            ]);

            // Attach the new tenant to the currently authenticated user
            $user->tenants()->attach($tenant->id);

            // Emit an event to notify that the tenant was created successfully
            $this->emit('success', 'Tenant successfully added');
        } else {
            // Notify the user that the tenant already exists
            $this->emit('error', 'Tenant already exists');
        }
    }

    // Reset the form fields and exit edit mode
    $this->reset(['name', 'edit_mode']);
}


    public function deleteTenant($id)
    {
        // Find the tenant by ID
        $tenant = Tenant::find($id);

        // Detach the tenant from the currently authenticated user
        Auth::user()->tenants()->detach($tenant->id);

        // Delete the tenant if it's not associated with any users
        if ($tenant->users->isEmpty()) {
            $tenant->delete();
        }

        // Emit a success event with a message
        $this->emit('success', 'Tenant successfully deleted');
    }

    public function updateTenant($id)
    {
        $this->edit_mode = true;

        $tenant = Tenant::find($id);

        // Ensure the user has a relationship with this tenant
        if (Auth::user()->tenants->contains($tenant)) {
            $this->tenantId = $id;
            $this->name = $tenant->name;
        } else {
            // Handle the case where the user does not have access to edit this tenant
            // You can show an error message or perform some other action
        }
    }

    public function render()
    {
        return view('livewire.tenant.tenant-modal');
    }
}