<?php

namespace App\Http\Livewire\Contact;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Validation\Rule;

class AddContactModal extends Component
{
    public $name;
    public $email;
    public $phone;
    public $notes;

    public function submit()
    {
        // Validate the data
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('contacts')],
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        // Save the new contact to the database
        Contact::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'notes' => $this->notes,
        ]);

        // Emit an event to notify that the contact was created successfully
        $this->emit('success');

        // Reset the form fields
        $this->reset(['name', 'email', 'phone', 'notes']);
    }

    public function render()
    {
        return view('livewire.contact.add-contact-modal');
    }
}