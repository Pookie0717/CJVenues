<?php

namespace App\Http\Livewire\Quote;

use Livewire\Component;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\Option;
use App\Models\VenueArea;
use App\Models\Venue;
use App\Models\Season;
use App\Models\EventType;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;


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
    public $quote_number;
    public $selectedOptions = [];
    public $options_ids;
    public $options_values;
    public $calculated_price;
    public $discount_type;
    public $discount;
    public $price;
    public $price_venue;
    public $price_options;

    public $edit_mode = false;

    protected $listeners = [
        'delete_quote' => 'deleteQuote',
        'update_quote' => 'updateQuote',
    ];

    public function updateSelectedOption($optionId, $value)
    {
        $this->selectedOptions[$optionId] = $value;
    }

    public function updateCheckboxOption($optionId, $value, $checked)
    {
        if (!isset($this->selectedOptions[$optionId])) {
            $this->selectedOptions[$optionId] = [];
        }

        if ($checked) {
            $this->selectedOptions[$optionId][] = $value;
        } else {
            $key = array_search($value, $this->selectedOptions[$optionId]);
            if ($key !== false) {
                unset($this->selectedOptions[$optionId][$key]);
            }
        }
    }

    
    public function calculatePriceOptions($dateFrom, $dateTo, $timeFrom, $timeTo, $optionIds, $optionValues)
    {
        // Convert the date strings to Carbon instances
        $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom);
        $dateTo = Carbon::createFromFormat('d-m-Y', $dateTo);

        // Get all seasons from the database
        $allSeasons = Season::orderBy('priority', 'desc')->get();

        // Convert the option IDs to an array
        $optionIdsArray = explode('|', $optionIds);

        // Initialize the total price for options
        $totalPrice = 0;

        // Iterate through the seasons to find a matching price for each option
        foreach ($optionIdsArray as $optionId) {
            foreach ($allSeasons as $season) {
                // Check if there is a price associated with the option for this season
                $optionPrice = $this->getOptionPriceForSeason($optionId, $season->id);

                if ($optionPrice) {
                    // Get the multiplier type (daily, hourly, per event) and value
                    $multiplierType = $optionPrice->multiplier;
                    $multiplierValue = (float)$optionPrice->price;

                    // Calculate the price based on the multiplier type
                    switch ($multiplierType) {
                        case 'daily':
                            $totalPrice += $multiplierValue * $this->calculateNumberOfDays($dateFrom, $dateTo);
                            break;
                        case 'hourly':
                            $totalPrice += $multiplierValue * $this->calculateNumberOfHours($dateFrom, $timeFrom, $dateTo, $timeTo);
                            break;
                        case 'event':
                            $totalPrice += $multiplierValue;
                            break;
                    }
                }
            }
        }

        return $totalPrice;
    }

    private function getOptionPriceForSeason($optionId, $seasonId)
    {
        return Option::find($optionId)->prices()
            ->where('type', 'option')
            ->where('season_id', $seasonId)
            ->first();
    }


    public function calculatePriceVenue($dateFrom, $dateTo, $timeFrom, $timeTo, $areaId)
    {
        // Get the associated venue and area
        $venue = Venue::whereHas('areas', function ($query) use ($areaId) {
            $query->where('id', $areaId);
        })->first();

        $area = VenueArea::find($areaId);

        // Convert the date strings to Carbon instances
        $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom);
        $dateTo = Carbon::createFromFormat('d-m-Y', $dateTo);

        // Get all seasons from the database
        $allSeasons = Season::orderBy('priority', 'desc')->get();

        // Filter seasons based on the date range
        $seasons = $allSeasons->filter(function ($season) use ($dateFrom, $dateTo) {
            $seasonStartDate = Carbon::createFromFormat('d-m-Y', $season->date_from);
            $seasonEndDate = Carbon::createFromFormat('d-m-Y', $season->date_to);

            // Check if the date range falls within this season
            $isWithinSeason = $dateFrom->between($seasonStartDate, $seasonEndDate) ||
                              $dateTo->between($seasonStartDate, $seasonEndDate) ||
                              ($dateFrom <= $seasonStartDate && $dateTo >= $seasonEndDate);

            return $isWithinSeason;
        });

        // Iterate through the seasons to find a matching price
        foreach ($seasons as $season) {
            // Check if there is a price associated with the area for this season
            $areaPrice = $area->prices()
                ->where('type', 'area')
                ->where('season_id', $season->id)
                ->first();

            // Check if there is a price associated with the venue for this season
            $venuePrice = $venue->prices()
                ->where('type', 'venue')
                ->where('season_id', $season->id)
                ->first();

            // If no price is found for this season and area or venue, move to the next season
            if (!$areaPrice && !$venuePrice) {
                continue;
            }

            // Determine which price to use (area or venue) based on priority
            $price = $areaPrice ?? $venuePrice;

            // Get the multiplier type (daily, hourly, per event) and value
            $multiplierType = $price->multiplier;
            $multiplierValue = (float)$price->price;

            // Calculate the price based on the multiplier type
            switch ($multiplierType) {
                case 'daily':
                    return $multiplierValue * $this->calculateNumberOfDays($dateFrom, $dateTo);
                case 'hourly':
                    return $multiplierValue * $this->calculateNumberOfHours($dateFrom, $timeFrom, $dateTo, $timeTo);
                case 'event':
                    return $multiplierValue;
            }
        }

        // If no matching price is found for any of the seasons, return 0
        return 0;
    }


    // Helper method to calculate the number of days between date_from and date_to
    private function calculateNumberOfDays($dateFrom, $dateTo)
    {
        $startDate = $dateFrom;
        $endDate = $dateTo;

        $diffInDays = $startDate->diffInDays($endDate);

        // Ensure at least 1 day is counted
        return max($diffInDays, 1);
    }

    // Helper method to calculate the number of hours between time_from and time_to
    private function calculateNumberOfHours($dateFrom, $timeFrom, $dateTo, $timeTo)
    {
       // $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom);
        //$dateTo = Carbon::createFromFormat('d-m-Y', $dateTo);

        // Set the time on $dateFrom and $dateTo using setTimeFromTimeString
        $dateFrom->setTimeFromTimeString($timeFrom);
        $dateTo->setTimeFromTimeString($timeTo);

        $dateTimeFrom = $dateFrom->setTimeFromTimeString($timeFrom);
        $dateTimeTo = $dateTo->setTimeFromTimeString($timeTo);

        $diffInSeconds = max($dateTimeTo->diffInSeconds($dateTimeFrom), 0);

        // Convert seconds to hours
        return $diffInSeconds / 3600;
    }

    public function submit()
    {
        // Validate the data
        $this->validate([
            'contact_id' => 'required',
            'area_id' => 'required',
            'event_type' => 'required',
        ]);

        if ($this->edit_mode) {
            // If in edit mode, create a new quote record the existing quote record
            $quote = Quote::find($this->quoteId);
            $newVersion = $quote->version + 1;

            $priceVenue = $this->calculatePriceVenue($this->date_from, $this->date_to, $this->time_from, $this->time_to, $this->area_id);;
            // Convert selected options to a comma-separated string format
            $optionIds = implode('|', array_keys($this->selectedOptions));
            $optionValues = implode('|', array_values($this->selectedOptions));
            
            $priceOptions = $this->calculatePriceOptions($this->date_from, $this->date_to, $this->time_from, $this->time_to, $optionIds,$optionValues);
;
            $calculatedPrice = $priceVenue + $priceOptions;
           
            $price = $calculatedPrice;

            // Save the old quote to the database
            Quote::create([
                'contact_id' => $quote->contact_id,
                'status' => 'Archived',
                'version' => $quote->version,
                'date_from' => $quote->date_from,
                'date_to' => $quote->date_to,
                'time_from' => $quote->time_from,
                'time_to' => $quote->time_to,
                'area_id' => $quote->area_id,
                'event_type' => $quote->event_type,
                'quote_number' => $quote->quote_number,
                'calculated_price' =>  $quote->calculated_price,
                'discount_type' => $quote->discount_type,
                'discount' => $quote->discount,
                'price' => $quote->price,
                'price_venue' => $quote->price_venue,
                'price_options' => $quote->price_options,
                'options_ids' => $quote->options_ids,
                'options_values' => $quote->options_values,
            ]);

            $quote->update([
                'contact_id' => $this->contact_id,
                'status' => 'Edited',
                'version' => $newVersion,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'time_from' => $this->time_from,
                'time_to' => $this->time_to,
                'area_id' => $this->area_id,
                'event_type' => $this->event_type,
                'calculated_price' => $calculatedPrice,
                'discount_type' => $this->discount_type,
                'discount' => $this->discount,
                'price' => $price,
                'price_venue' => $priceVenue,
                'price_options' => $priceOptions,
                'options_ids' => $optionIds,
                'options_values' => $optionValues,
            ]);

            // Emit an event to notify that the quote was updated successfully
            $this->emit('success', 'Quote successfully updated');
        } else {
            // Retrieve the current quote number
            $currentQuoteNumber = DB::table('system_information')->where('key', 'current_quote_number')->value('value');
            $newQuoteNumber = $currentQuoteNumber ? $currentQuoteNumber + 1 : 1;

            // Convert selected options to a comma-separated string format
            $optionIds = implode('|', array_keys($this->selectedOptions));
            $optionValues = implode('|', array_values($this->selectedOptions));

            Log::info('Selected Options:', ['data' => $this->selectedOptions]);
            Log::info('Option IDs:', ['data' => $optionIds]);
            Log::info('Option Values:', ['data' => $optionValues]);

            $priceVenue = $this->calculatePriceVenue($this->date_from, $this->date_to, $this->time_from, $this->time_to, $this->area_id);;
            $priceOptions = $this->calculatePriceOptions($optionIds,$optionValues);
            $calculatedPrice = $priceVenue + $priceOptions;
            $price = $calculatedPrice;

            // Save the new quote to the database
            Quote::create([
                'contact_id' => $this->contact_id,
                'status' => 'Unsent',
                'version' => '1',
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'time_from' => $this->time_from,
                'time_to' => $this->time_to,
                'area_id' => $this->area_id,
                'event_type' => $this->event_type,
                'quote_number' => $newQuoteNumber, // Assign the new quote number
                'calculated_price' =>$calculatedPrice,
                'discount_type' => $this->discount_type,
                'discount' => $this->discount,
                'price' => $price,
                'price_venue' => $priceVenue,
                'price_options' => $priceOptions,
                'options_ids' => $optionIds,
                'options_values' => $optionValues,
            ]);

            // Update the current quote number in the system_information table
            DB::table('system_information')->where('key', 'current_quote_number')->update(['value' => $newQuoteNumber]);

            // Emit an event to notify that the quote was created successfully
            $this->emit('success', 'Quote successfully added');

        }

        // Reset the form fields
        $this->reset(['contact_id', 'status', 'version', 'date_from', 'date_to', 'time_from', 'time_to', 'area_id', 'event_type', 'edit_mode', 'quoteId', 'calculated_price', 'discount_type', 'discount', 'price', 'price_venue', 'price_options', 'options_ids' , 'options_values']);
    }

    public function deleteQuote($id)
    {
        // Find the quote by ID
        $quote = Quote::find($id);

        if ($quote) {
            // Find all quotes with the same quote_number
            $quotesToDelete = Quote::where('quote_number', $quote->quote_number)->get();

            // Delete all quotes with the same quote_number
            foreach ($quotesToDelete as $quoteToDelete) {
                $quoteToDelete->delete();
            }

            // Emit a success event with a message
            $this->emit('success', 'Quote successfully deleted');
        } else {
            $this->emit('error', 'Quote not found');

        }
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
        $this->calculated_price = $quote->calculated_price;
        $this->discount_type = $quote->discount_type;
        $this->discount = $quote->discount;
        $this->price = $quote->price;
    }

    public function render()
    {

        // Load contacts, venues, venue areas, and event types for selection
        $contacts = Contact::all();
        $venues = Venue::all();
        $venueAreas = VenueArea::all();
        $eventTypes = EventType::all();
        $options = Option::all();

        return view('livewire.quote.add-quote-modal', compact('contacts', 'venueAreas', 'venues', 'eventTypes', 'options'));
    }

}
