<?php

namespace App\Http\Livewire\Contact;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Validation\Rule;

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

    public function submit()
    {
        // Validate the data
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('contacts')],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|string|max:20',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

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
            'state' => $this->state,
            'country' => $this->country,
            'notes' => $this->notes,
        ]);

        // Emit an event to notify that the contact was created successfully
        $this->emit('success');

        // Reset the form fields
        $this->reset(['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postcode', 'state', 'country', 'notes']);
    }

    public function render()
    {
        return view('livewire.contact.add-contact-modal');
    }
}
