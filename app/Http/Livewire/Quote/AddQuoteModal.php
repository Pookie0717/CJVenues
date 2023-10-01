<?php

namespace App\Http\Livewire\Quote;

use Livewire\Component;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\VenueArea;
use App\Models\Venue;
use App\Models\EventType;
use Illuminate\Validation\Rule;

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
    public $quoteId;

    public $edit_mode = false;

    protected $listeners = [
        'delete_quote' => 'deleteQuote',
        'update_quote' => 'updateQuote',
    ];

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
            'area_id' => 'required|string|max:255',
            'event_type' => 'required|string|max:255',
        ]);

        if ($this->edit_mode) {
            // If in edit mode, update the existing quote record
            $quote = Quote::find($this->quoteId);
            $quote->update([
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

            // Emit an event to notify that the quote was updated successfully
            $this->emit('success', 'Quote successfully updated');
        } else {
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
            $this->emit('success', 'Quote successfully added');
        }

        // Reset the form fields
        $this->reset(['contact_id', 'status', 'version', 'date_from', 'date_to', 'time_from', 'time_to', 'area_id', 'event_type', 'edit_mode', 'quoteId']);
    }

    public function deleteQuote($id)
    {
        // Find the quote by ID
        $quote = Quote::find($id);

        // Delete the quote
        $quote->delete();

        // Emit a success event with a message
        $this->emit('success', 'Quote successfully deleted');
    }

    public function updateQuote($id)
    {
        $this->edit_mode = true;
        $quote = Quote::find($id);

        $this->contact_id = $quote->contact_id;
        $this->status = $quote->status;
        $this->version = $quote->version;
        $this->date_from = $quote->date_from;
        $this->date_to = $quote->date_to;
        $this->time_from = $quote->time_from;
        $this->time_to = $quote->time_to;
        $this->area_id = $quote->area_id;
        $this->event_type = $quote->event_type;
        $this->quoteId = $quote->id;
    }

    public function render()
    {
        // Load contacts and areas for selection
        $contacts = Contact::all();
        $venues = Venue::all();
        $venueAreas = VenueArea::all();
        $eventTypes = EventType::all();

        return view('livewire.quote.add-quote-modal', compact('contacts', 'venueAreas','venues','eventTypes'));
    }
}
