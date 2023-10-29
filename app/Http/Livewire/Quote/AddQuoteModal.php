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
    public $event_name;
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
    public $selectedVenueId;
    public $people;
    public $eventName = '';
    public $eventTypes = [];

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

   public function calculatePriceOptions($dateFrom, $dateTo, $timeFrom, $timeTo, $optionIds, $optionValues, $people)
    {
        $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom);
        $dateTo = Carbon::createFromFormat('d-m-Y', $dateTo);
        $allSeasons = Season::orderBy('priority', 'desc')->get();
        $optionIdsArray = explode('|', $optionIds);
        $optionValuesArray = explode('|', $optionValues);
        $individualPrices = [];

        foreach ($optionIdsArray as $index => $optionId) {
            $optionTotalPrice = 0;

            if (!isset($optionValuesArray[$index]) || $optionValuesArray[$index] == '') { 
                $individualPrices[] = 0;
                continue;
            }

            foreach ($allSeasons as $season) {
                $optionPrice = $this->getOptionPriceForSeason($optionId, $season->id);
                $optionType = $this->getOptionType($optionId);

                if ($optionPrice) {
                    $multiplierValue = (float)$optionPrice->price;
                    $optionTotalPrice += $this->calculateOptionPrice(
                        $optionType,
                        $optionValuesArray[$index],
                        $optionPrice,
                        $optionPrice->multiplier,
                        $multiplierValue,
                        $dateFrom,
                        $dateTo,
                        $timeFrom,
                        $timeTo,
                        $optionId,
                        $people
                    );
                }
            }
            $individualPrices[] = $optionTotalPrice;
        }

        return implode('|', $individualPrices);
    }

    private function calculateOptionPrice($optionType, $optionValue, $optionPrice, $multiplierType, $multiplierValue, $dateFrom, $dateTo, $timeFrom, $timeTo, $optionId, $people)
    {
        $price = 0;

        switch ($multiplierType) {
            case 'daily':
            case 'daily_pp':
                $days = $this->calculateNumberOfDays($dateFrom, $dateTo);
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $days, $optionId);
                break;

            case 'hourly':
            case 'hourly_pp':
                $hours = $this->calculateNumberOfHours($dateFrom, $timeFrom, $dateTo, $timeTo);
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $hours, $optionId);
                break;

            case 'event':
            case 'event_pp':
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, 1, $optionId);
                break;
        }
        if (str_ends_with($multiplierType, '_pp')) {
            $price *= $people;
        }
        return $price;
    }

    private function calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $quantity, $optionId)
    {
        $price = 0;

        if ($optionType === 'yes_no') {
            $price = $optionValue === 'yes' ? $multiplierValue * $quantity : 0;
        } elseif ($optionType === 'number') {
            $price = $multiplierValue * (float)$optionValue * $quantity;
        } elseif ($optionType === 'radio' || $optionType === 'checkbox') {
            $optionValues = explode('|', $this->getOptionValues($optionId));
            $selectedValue = $optionValue;
            $prices = explode('|', $optionPrice->price);
            $selectedValueIndex = array_search($selectedValue, $optionValues);

            if ($selectedValueIndex !== false && isset($prices[$selectedValueIndex])) {
                $selectedPrice = (float)$prices[$selectedValueIndex];
                $price = $selectedPrice * $quantity;
            } else {
                // Log when the selected value index is not found or the price is not set
                \Log::error("Selected value index not found or price not set for selected value.");
            }
        }

        return $price;

    }

    private function getOptionValues($optionId)
    {
        $option = Option::find($optionId);

        if ($option) {
            return $option->values;
        }

        return null; // Return null or handle the case where the option is not found
    }

    private function getOptionType($optionId)
    {
        // Retrieve the option by ID from your database
        $option = Option::find($optionId);

        // Check if the option exists and has a valid type
        if ($option && in_array($option->type, ['yes_no', 'number', 'radio', 'checkbox'])) {
            return $option->type;
        }

        // Default to a suitable option type if not found
        return 'unknown';
    }
    private function getOptionPriceForSeason($optionId, $seasonId)
    {
        return Option::find($optionId)->prices()
            ->where('type', 'option')
            ->where('season_id', $seasonId)
            ->first();
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

            $this->eventTypes = [];
            // Retrieve the current quote number
            $currentQuoteNumber = DB::table('system_information')->where('key', 'current_quote_number')->value('value');
            $newQuoteNumber = $currentQuoteNumber ? $currentQuoteNumber + 1 : 1;

            // Convert selected options to a comma-separated string format
            $optionIds = implode('|', array_keys($this->selectedOptions));
            $optionValues = implode('|', array_values($this->selectedOptions));

            $priceVenue = $this->calculatePriceVenue($this->date_from, $this->date_to, $this->time_from, $this->time_to, $this->area_id);;
            
            $priceOptionsString = $this->calculatePriceOptions($this->date_from, $this->date_to, $this->time_from, $this->time_to, $optionIds, $optionValues, $this->people);

            $priceOptionsArray = explode('|', $priceOptionsString);

            // Calculate the total of all the option prices
            $priceOptions = array_sum(array_map(function($price) {
                return (float) $price;
            }, $priceOptionsArray));

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
                'event_name' => $this->eventName,
                'people' => $this->people,
                'quote_number' => $newQuoteNumber, // Assign the new quote number
                'calculated_price' =>$calculatedPrice,
                'discount_type' => $this->discount_type,
                'discount' => $this->discount,
                'price' => $price,
                'price_venue' => $priceVenue,
                'price_options' => $priceOptionsString,
                'options_ids' => $optionIds,
                'options_values' => $optionValues,
            ]);

            // Update the current quote number in the system_information table
            DB::table('system_information')->where('key', 'current_quote_number')->update(['value' => $newQuoteNumber]);

            // Emit an event to notify that the quote was created successfully
            $this->emit('success', 'Quote successfully added');

        

        // Reset the form fields
        $this->reset(['contact_id', 'status', 'version', 'date_from', 'date_to', 'time_from', 'time_to', 'area_id', 'event_type','event_name', 'edit_mode', 'quoteId', 'calculated_price', 'people', 'discount_type', 'discount', 'price', 'price_venue', 'price_options', 'options_ids' , 'options_values']);
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
        $this->event_name = $quote->eventName;
        $this->people = $quote->people;
        $this->quoteId = $quote->id;
        $this->calculated_price = $quote->calculated_price;
        $this->discount_type = $quote->discount_type;
        $this->discount = $quote->discount;
        $this->price = $quote->price;
    }

    public function render()
    {
        $contacts = Contact::all();
        $venues = Venue::all();
        $venueAreas = VenueArea::all();
        $eventTypes = EventType::all();

        // Filter areas based on the selected venue ID
        $filteredAreas = $venueAreas->where('venue_id', $this->selectedVenueId);


        $options = $this->loadOptions();

        return view('livewire.quote.add-quote-modal', compact('contacts', 'filteredAreas', 'venues', 'eventTypes', 'options'));
    }

    public function updatedAreaId()
    {
        $this->loadOptions();
    }

    public function updatedSelectedVenueId()
    {
        $this->loadOptions();
    }

    public function updatedSeasonId()
    {
        $this->loadOptions();
    }

    public function loadEventTypes()
    {
        $this->eventTypes = EventType::where('event_name', 'like', '%' . $this->eventName . '%')->get();
    }

    private function loadOptions()
    {
        // Check if the required fields are set
        if (!$this->date_from || !$this->area_id) {
            $this->options = collect(); // No options to display if date and area are not set
            return;
        }

        // Get the season for the selected date
        $season = $this->getSeasonForDate($this->date_from);

        // Find the associated venue ID for the selected area
        $selectedArea = VenueArea::find($this->area_id);
        $selectedVenueId = optional($selectedArea->venue)->id;

        // Query the options based on selected venue, season, and position
        $this->options = Option::orderBy('position')
            ->when($selectedVenueId, function ($query) use ($selectedVenueId) {
                return $query->where('venue_id', $selectedVenueId);
            })
            ->when($season, function ($query) use ($season) {
                return $query->where('season_id', $season->id);
            })
            ->get();
    }



    private function getSeasonForDate($date)
    {
        $date = Carbon::createFromFormat('d-m-Y', $date);

        return Season::where('date_from', '<=', $date)
            ->orderBy('priority', 'desc')
            ->first();
    }

}
