<?php

namespace App\Http\Livewire\Quote;

use Livewire\Component;
use App\Models\Quote;
use App\Models\Contact; // Don't forget to import the Contact model

class AddQuoteModal extends Component
{
    public $contact_id;
    public $content;
    public $contacts; // Declare the contacts property

    protected $rules = [
        'contact_id' => 'required|integer|exists:contacts,id',
        'content' => 'required|string',
    ];

    public function mount()
    {
        $this->contacts = Contact::all(); // Load all contacts
    }

    public function render()
    {
        return view('livewire.quote.add-quote-modal');
    }

    public function submit()
    {
        $this->validate();

        Quote::create([
            'contact_id' => $this->contact_id,
            'content' => $this->content,
        ]);

        $this->emit('success', __('New quote created'));
        $this->reset();
    }
}
