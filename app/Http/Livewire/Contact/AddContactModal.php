<?php

namespace App\Http\Livewire\Contact;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use PragmaRX\Countries\Package\Countries;
use Illuminate\Support\Facades\Session;


class AddContactModal extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $postcode;
    public $state;
    public $country;
    public $notes;
    public $contactId;
    public $tenant_id;
    
    public $countries;
    public $states = [];

    public $edit_mode = false;

    public function mount()
    {
        $country = new Countries();
        $this->countries = $country->all()->pluck('name.common', 'cca3')->toArray();
        asort($this->countries);
        $this->states = [];
    }

    protected $listeners = [
        'create_contact' => 'createContact',
        'delete_contact' => 'deleteContact',
        'update_contact' => 'updateContact',
    ];

    public function submit()
    {
        // Define the validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255'],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|string|max:20',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ];

        // Add the email uniqueness rule conditionally
        if (!$this->edit_mode) {
            $rules['email'][] = Rule::unique('contacts');
        }

        // Validate the data
        $this->validate($rules);

        if ($this->edit_mode) {
            // If in edit mode, update the existing contact record
            $contact = Contact::find($this->contactId);
            $contact->update([
                'tenant_id' => $this->tenant_id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'name' => $this->first_name . ' ' . $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'postcode' => $this->postcode,
                'state' => $this->state,
                'country' => $this->country,
                'notes' => $this->notes,
            ]);

            // Emit an event to notify that the contact was updated successfully
            $this->emit('success', trans('contact.contactsuccessfullyupdated'));
        } else {
            // Save the new contact to the database
            Contact::create([
                'tenant_id' => $this->tenant_id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'name' => $this->first_name . ' ' . $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'postcode' => $this->postcode,
                'state' => $this->state,
                'country' => $this->country,
                'notes' => $this->notes,
            ]);

            // Emit an event to notify that the contact was created successfully
            $this->emit('success', 'Contact successfully added');
        }

        // Reset the form fields
        $this->reset(['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postcode', 'state', 'country', 'notes', 'states']);
    }

    public function createContact() {
        $this->edit_mode = false;
        $this->tenant_id = Session::get('current_tenant_id');
        $this->reset(['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postcode', 'state', 'country', 'notes', 'states']);
    }

    public function deleteContact($id)
    {
        // Find the contact by ID
        $contact = Contact::find($id);

        // Delete the contact
        $contact->delete();

        // Emit a success event with a message
        $this->emit('success', 'Contact successfully deleted');
    }

    public function updateContact($id)
    {
        $this->edit_mode = true;

        $contact = Contact::find($id);

        $this->contactId = $id; // Set the contactId property
        $this->tenant_id = $contact->tenant_id;
        $this->first_name = $contact->first_name;
        $this->last_name = $contact->last_name;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->address = $contact->address;
        $this->contact = $contact->contact;
        $this->city = $contact->city;
        $this->postcode = $contact->postcode;
        $this->state = $contact->state;
        $this->country = $contact->country;
        $this->notes = $contact->notes;
        $this->updatedCountry($contact->country);
    }

    public function updatedCountry($countryCode)
    {
        $country = new Countries();
        $country = $country->where('cca3', $countryCode)->first();
        if($country) {
            $this->states = sizeof($country) > 0 ? $country->hydrate('states')->states->pluck('name', 'postal')->toArray(): [];
        }
    }

    public function render()
    {
        return view('livewire.contact.add-contact-modal');
    }
}
