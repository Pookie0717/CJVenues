<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use App\Models\Tenant;
use App\Models\TenantUser;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

class AddUserModal extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $role;
    public $avatar;
    public $saved_avatar;
    public $tenants;
    public $selectedTenants;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'role' => 'required|string',
        'avatar' => 'nullable|sometimes|image|max:1024',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
    ];

    public function render()
    {
        $roles = Role::all();

        // $this->tenants = DB::select(DB::raw('SELECT e.employee_name AS employee, m.employee_name AS manager
        // FROM employees e
        // LEFT JOIN employees m ON e.manager_id = m.employee_id'))
        $this->tenants = Tenant::leftJoin('tenants as t', 'tenants.parent_id', '=', 't.id')
        ->select('tenants.id', DB::raw('CONCAT(CASE WHEN t.name IS NULL THEN "" ELSE CONCAT(t.name, " - ") END, tenants.name) AS name'))
        ->orderBy('name')
        ->get();

        $roles_description = [
            'administrator' => 'Best for business owners and company administrators',
            'developer' => 'Best for developers or people primarily using the API',
            'analyst' => 'Best for people who need full access to analytics data, but don\'t need to update business settings',
            'support' => 'Best for employees who regularly refund payments and respond to disputes',
            'trial' => 'Best for people who need to preview content data, but don\'t need to make any updates',
        ];

        foreach ($roles as $i => $role) {
            $roles[$i]->description = $roles_description[$role->name] ?? '';
        }

        return view('livewire.user.add-user-modal', compact('roles'));
    }

    public function submit()
    {
        // Validate the form input data
        $this->validate();

        // Get the current tenant_id from wherever it's available in your application
        $currentTenantId = Session::get('current_tenant_id'); // Adjust this based on your actual implementation

        DB::transaction(function () use ($currentTenantId) {
            // Prepare the data for creating a new user
            $data = [
                'name' => $this->name,
                'tenant_id' => $currentTenantId, // Set the tenant_id
            ];

            if ($this->avatar) {
                $data['profile_photo_path'] = $this->avatar->store('avatars', 'public');
            } else {
                $data['profile_photo_path'] = null;
            }

            if (!$this->edit_mode) {
                $data['password'] = Hash::make($this->email);
            }

            // Create a new user record in the database
            $user = User::updateOrCreate([
                'email' => $this->email,
            ], $data);

            $user->tenants()->sync($this->selectedTenants);

            if ($this->edit_mode) {
                // Assign selected role for user
                $user->syncRoles($this->role);

                // Emit a success event with a message
                $this->emit('success', __('User updated'));
            } else {
                // Assign selected role for user
                $user->assignRole($this->role);

                // Send a password reset link to the user's email
                Password::sendResetLink($user->only('email'));

                // Emit a success event with a message
                $this->emit('success', __('New user created'));
            }
        });

        // Reset the form fields after successful submission
        $this->reset();
        $this->selectedTenants = [];
    }

    public function deleteUser($id)
    {
        // Prevent deletion of current user
        if ($id == Auth::id()) {
            $this->emit('error', 'User cannot be deleted');
            return;
        }

        // Delete related records in the `tenant_user` table
        TenantUser::where('user_id', $id)->delete();

        // Delete the user record with the specified ID
        User::destroy($id);

        // Emit a success event with a message
        $this->emit('success', 'User successfully deleted');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $user = User::find($id);

        $this->saved_avatar = $user->profile_photo_url;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles?->first()->name ?? '';
        $this->selectedTenants = $user->tenants->pluck('id')->toArray();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
