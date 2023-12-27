<?php

namespace App\Http\Livewire\Contact;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use PragmaRX\Countries\Package\Countries;


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
    public $selectedCountry;
    public $selectedState;
    public $contactId;
    protected $countries;
    public $states = [];
    public $cities = [];

    public $edit_mode = false;

    public function getCountriesProperty()
    {
        return $this->countries ?? [];
    }

    public function mount()
    {
        $countries = new Countries();
        $this->countries = $countries->all()->pluck('name.common', 'cca3')->toArray();
        asort($this->countries);
        $this->states = ['Select a state'];
        $this->cities = [];
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
            'selectedState' => 'required|string|max:255',
            'selectedCountry' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ];

        // Add the email uniqueness rule conditionally
        if (!$this->edit_mode) {
            //$rules['email'][] = Rule::unique('contacts');
        }

        // Validate the data
        $this->validate($rules);

        if ($this->edit_mode) {
            // If in edit mode, update the existing contact record
            $contact = Contact::find($this->contactId);
            $contact->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'name' => $this->first_name . ' ' . $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'postcode' => $this->postcode,
                'state' => $this->selectedState,
                'country' => $this->selectedCountry,
                'notes' => $this->notes,
            ]);

            // Emit an event to notify that the contact was updated successfully
            $this->emit('success', trans('contact.contactsuccessfullyupdated'));
        } else {
            // Save the new contact to the database
            Contact::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'name' => $this->first_name . ' ' . $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => $this->city,
                'postcode' => $this->postcode,
                'state' => $this->selectedState,
                'country' => $this->selectedCountry,
                'notes' => $this->notes,
            ]);

            // Emit an event to notify that the contact was created successfully
            $this->emit('success', 'Contact successfully added');
        }

    
        // Reset the form fields
        $this->reset(['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postcode', 'state', 'country', 'notes']);
    }

    public function createContact() {
        $this->reset(['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postcode', 'state', 'country', 'notes']);
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

        $countries = new Countries();
        $this->countries = $countries->all()->pluck('name.common', 'cca3')->toArray();
        asort($this->countries);
        $this->states = [];
        $this->cities = [];
        $this->contactId = $id; // Set the contactId property
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
        $this->selectedCountry = $contact->country;
        $this->updatedSelectedCountry($contact->country);
        $this->selectedState = $contact->state;
    }

    public function updatedSelectedCountry($countryCode)
    {
        $countries = new Countries();
        $this->states = $countries->where('cca3', $countryCode)->first()->hydrate('states')->states->pluck('name', 'postal')->toArray();
        $this->countries = $countries->all()->pluck('name.common', 'cca3')->toArray();
        $this->selectedState = null;
        $this->selectedCountry = $countryCode;
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
     return view('livewire.contact.add-contact-modal', [
            'countries' => $this->getCountriesProperty(),
        ]);
    }
}
