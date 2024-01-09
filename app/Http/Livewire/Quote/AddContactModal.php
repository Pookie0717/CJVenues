<?php

namespace App\Http\Livewire\Quote;

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

       // Save the new contact to the database
       Contact::create([
            'tenant_id' => Session::get('current_tenant_id'),
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
        $this->emit('quote_contact_success', 'Contact successfully added');

        // Reset the form fields
        $this->reset(['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postcode', 'state', 'country', 'notes']);
    }

    public function render()
    {
        $country = new Countries();
        $countries = $country->all()->pluck('name.common', 'cca3')->toArray();
        asort($countries);

        $country = $country->where('cca3', $this->country)->first();
        $states = [];
        if($country) {
            $states = sizeof($country) > 0 ? $country->hydrate('states')->states->pluck('name', 'postal')->toArray(): [];
        }
       
        return view('livewire.quote.add-contact-modal', compact('countries', 'states'));
    }
}
