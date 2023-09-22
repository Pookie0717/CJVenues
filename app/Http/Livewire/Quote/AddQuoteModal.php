<?php

namespace App\Http\Livewire\Quote;

use Livewire\Component;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\VenueArea; // Ensure you have the correct model name for areas

class AddQuoteModal extends Component
{
    public $contact_id;
    public $status;
    public $version;
    public $date_from;
    public $date_to;
    public $time_from;
    public $time_to;
    public $area_id;
    public $event_type;
    
    public function submit()
    {
        // Validate the data
        $this->validate([
            'contact_id' => 'required|exists:contacts,id',
            'status' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i|after:time_from',
            'area_id' => 'required|exists:venues_areas,id',
            'event_type' => 'required|string|max:255',
        ]);

        // Save the new quote to the database
        Quote::create([
            'contact_id' => $this->contact_id,
            'status' => $this->status,
            'version' => $this->version,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'area_id' => $this->area_id,
            'event_type' => $this->event_type,
        ]);

        // Emit an event to notify that the quote was created successfully
        $this->emit('success');

        // Reset the form fields
        $this->reset(['contact_id', 'status', 'version', 'date_from', 'date_to', 'time_from', 'time_to', 'area_id', 'event_type']);
    }

    public function render()
    {
        // Load contacts and areas for selection
        $contacts = Contact::all();
        $areas = VenueArea::all();
        
        return view('livewire.quote.add-quote-modal', compact('contacts', 'areas'));
    }
}
