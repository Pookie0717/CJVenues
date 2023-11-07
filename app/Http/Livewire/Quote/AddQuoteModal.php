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
use Illuminate\Support\Facades\Session;


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
    public $logicOptionValue;

    public $edit_mode = false;

    protected $listeners = [
        'delete_quote' => 'deleteQuote',
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

    private function getSeasonsForDateAndWeekday($date, $weekday)
    {
        // Convert the date to a valid format for comparison
        $formattedDate = $date->format('Y-m-d');
        $currentTenantId = Session::get('current_tenant_id');

        // Query for seasons that match the date, weekday, and tenant_id
        return Season::where('tenant_id', $currentTenantId)
            ->where(function ($query) use ($formattedDate, $weekday) {
                $query->where('date_from', '<=', $formattedDate)
                    ->where('date_to', '>=', $formattedDate)
                    ->where(function ($query) use ($weekday) {
                        $query->whereJsonContains('weekdays', [$weekday])
                            ->orWhere('weekdays', null); // Include seasons with no specific weekdays
                    });
            })->orderBy('priority', 'desc')->get();
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
        if ($option && in_array($option->type, ['yes_no', 'number', 'radio', 'checkbox', 'logic'])) {
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

        // Create Carbon instances for date and time
        $dateTimeFrom = $dateFrom->copy()->setTimeFromTimeString($timeFrom);
        $dateTimeTo = $dateTo->copy()->setTimeFromTimeString($timeTo);

        // Calculate the difference in hours
        $hoursDifference = $dateTimeTo->diffInHours($dateTimeFrom);

        return $hoursDifference;
    }

    public function calculatePriceVenue($dateFrom, $dateTo, $timeFrom, $timeTo, $areaId)
    {
        // Get the associated venue and area
        $venue = Venue::whereHas('areas', function ($query) use ($areaId) {
            $query->where('id', $areaId);
        })->first();

        $area = VenueArea::find($areaId);

        // Convert the date strings to Carbon instances
        $dateFromC = Carbon::createFromFormat('d-m-Y', $dateFrom);
        $dateToC = Carbon::createFromFormat('d-m-Y', $dateTo);

        // Initialize the total price
        $totalPrice = 0;

        // Iterate through each day in the date range
        $currentDate = $dateFromC->copy();
        while ($currentDate->lte($dateTo)) {
            // Get the day of the week for the current date (e.g., 'Mon')
            $currentDayOfWeek = $currentDate->format('D');

            // Get all seasons for the current date
            $matchingSeasons = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek);

            // Iterate through the matching seasons for the current date
            foreach ($matchingSeasons as $season) {
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

                // Calculate the price based on the multiplier type for the current day
                switch ($multiplierType) {
                    case 'daily':
                        $totalPrice += $multiplierValue;
                        break;
                    case 'hourly':
                        $hours = $this->calculateNumberOfHours($currentDate, $timeFrom, $currentDate, $timeTo);
                        $totalPrice += $multiplierValue * $hours;
                        break;
                    case 'event':
                        $totalPrice += $multiplierValue;
                        break;
                }
            }

            // Move to the next day
            $currentDate->addDay();
        }

        return $totalPrice;
    }

    private function calculateOptionPrice($optionType, $optionValue, $optionPrice, $multiplierType, $multiplierValue, $dateFrom, $dateTo, $timeFrom, $timeTo, $optionId, $people)
    {
        $price = 0;
        $days = $this->calculateNumberOfDays($dateFrom, $dateTo);
        $hours = $this->calculateNumberOfHours($dateFrom, $timeFrom, $dateTo, $timeTo);

        switch ($multiplierType) {
            case 'daily':
            case 'daily_pp':
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $days, $optionId, $people, $hours, $days);
                break;

            case 'hourly':
            case 'hourly_pp':
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $hours, $optionId, $people, $hours, $days);
                break;

            case 'event':
            case 'event_pp':
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, 1, $optionId, $people, $hours, $days);
                break;
        }
        if (str_ends_with($multiplierType, '_pp')) {
            $price *= $people;
        }
        return $price;
    }

    private function calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $quantity, $optionId, $people, $hours, $days)
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
            }
        } elseif ($optionType === 'logic') {

            $optionValues = explode('|', $this->getOptionValues($optionId));

            $logicOption = $this->getLogicOptionDetails($optionId, $people, $hours, $days);

            $logicOptionValue = $logicOption ? $optionValues[0] : $optionValues[1];

            $price = $multiplierValue * (float)$logicOptionValue * $quantity;

          }
        return $price;
    }

    // Add this private method to your Livewire component
    private function calculateLogicOptionValues($optionId)
    {
        $dateFrom = Carbon::createFromFormat('d-m-Y', $this->date_from);
        $dateTo = Carbon::createFromFormat('d-m-Y', $this->date_to);

        $days = $this->calculateNumberOfDays($dateFrom, $dateTo);
        $hours = $this->calculateNumberOfHours($dateFrom, $this->time_from, $dateTo, $this->time_to);

        $optionType = $this->getOptionType($optionId);
        if ($optionType === 'logic') {
            $optionValues = explode('|', $this->getOptionValues($optionId));
            $logicOption = $this->getLogicOptionDetails($optionId,  $this->people, $hours, $days);
            $logicOptionValue = $logicOption ? $optionValues[0] : $optionValues[1];
            return $logicOptionValue;
        } else {
            return null;
        }
    }

    // Function to retrieve logic option details based on $optionId (you need to implement this)
    private function getLogicOptionDetails($optionId, $people, $hours, $days)
    {
       // Use Eloquent to find the Option by its ID
        $option = Option::find($optionId);
        $logicExpression = $option->logic;
            // Replace field names with their respective values
    $logicExpression = str_replace('people', $people, $logicExpression);
    $logicExpression = str_replace('hours', $hours, $logicExpression);
    $logicExpression = str_replace('days', $days, $logicExpression);

    // Split the logic expression by "OR" operators
    $orConditions = preg_split('/\bOR\b/', $logicExpression);
    $result = false;

    foreach ($orConditions as $orCondition) {
        // Split each OR condition by "AND" operators
        $andConditions = preg_split('/\bAND\b/', $orCondition);

        $orResult = true; // Initialize the OR result as true

        foreach ($andConditions as $andCondition) {
            // Split each condition into field, operator, and value
            $parts = preg_split('/(<=|>=|==|!=|<|>)/', $andCondition, -1, PREG_SPLIT_DELIM_CAPTURE);

            if (count($parts) === 3) {
                list($field, $operator, $value) = array_map('trim', $parts);

                // Evaluate the condition
                switch ($operator) {
                    case '<':
                        $andResult = ($$field < $value);
                        break;
                    case '<=':
                        $andResult = ($$field <= $value);
                        break;
                    case '>':
                        $andResult = ($$field > $value);
                        break;
                    case '>=':
                        $andResult = ($$field >= $value);
                        break;
                    case '==':
                        $andResult = ($$field == $value);
                        break;
                    case '!=':
                        $andResult = ($$field != $value);
                        break;
                    default:
                        // Invalid operator; set result to false
                        $andResult = false;
                        break;
                }

                // If any AND condition is false, break out of the loop
                if (!$andResult) {
                    $orResult = false;
                    break;
                }

            }
        }

        // If any OR condition is true, set the final result to true
        if ($orResult) {
            $result = true;
            break;
        }
    }

    return $result;

    }


    public function calculatePriceOptions($dateFrom, $dateTo, $timeFrom, $timeTo, $optionIds, $optionValues, $people)
    {
        // Convert the date strings to Carbon instances
        $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom);
        $dateTo = Carbon::createFromFormat('d-m-Y', $dateTo);

        // Initialize the total price for options
        $totalPrice = 0;

        // Iterate through each day in the date range
        $currentDate = $dateFrom->copy();
        while ($currentDate->lte($dateTo)) {
            // Get the day of the week for the current date (e.g., 'Mon')
            $currentDayOfWeek = $currentDate->format('D');

            // Get all seasons for the current date
            $matchingSeasons = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek);

            // Iterate through the matching seasons for the current date
            foreach ($matchingSeasons as $season) {
                foreach (explode('|', $optionIds) as $index => $optionId) {
                    $optionValue = explode('|', $optionValues)[$index];

                    if (!isset($optionValue) || $optionValue == '') {
                        $optionValue = '0';
                        continue;
                    }

                    $optionPrice = $this->getOptionPriceForSeason($optionId, $season->id);
                    $optionType = $this->getOptionType($optionId);

                    if ($optionPrice) {
                        $multiplierValue = (float)$optionPrice->price;
                        $optionTotalPrice = $this->calculateOptionPrice(
                            $optionType,
                            $optionValue,
                            $optionPrice,
                            $optionPrice->multiplier,
                            $multiplierValue,
                            $currentDate,
                            $dateTo,
                            $timeFrom,
                            $timeTo,
                            $optionId,
                            $people
                        );
                        $totalPrice += $optionTotalPrice;
                        $individualPrices[] = [
                            'optionId' => $optionId,
                            'price' => $optionTotalPrice,
                        ];
                    }
                }
            }

            // Move to the next day
            $currentDate->addDay();
        }

        return [
            'totalPrice' => $totalPrice,
            'individualPrices' => $individualPrices,
        ];
    }

    public function submit()
    {
        // Validate the data
        $this->validate([
            'contact_id' => 'required',
            'area_id' => 'required',
            'event_type' => 'required',
            'people' => 'required',
        ]);

        $this->eventTypes = [];
        // Retrieve the current quote number
        $currentQuoteNumber = DB::table('system_information')->where('key', 'current_quote_number')->value('value');
        $newQuoteNumber = $currentQuoteNumber ? $currentQuoteNumber + 1 : 1;

        // Convert selected options to a comma-separated string format
        $optionIdsIm = implode('|', array_keys($this->selectedOptions));
        $optionValuesIm = implode('|', array_values($this->selectedOptions));

        $optionIds = explode('|', $optionIdsIm);
        $optionValues = explode('|', $optionValuesIm);

        $cleanedOptionIds = [];
        $cleanedOptionValues = [];

        foreach ($optionValues as $index => $value) {
            if ($value !== '') {
                $cleanedOptionIds[] = $optionIds[$index];
                $cleanedOptionValues[] = $value;
            }
        }

        $optionIds = implode('|', $cleanedOptionIds);
        $optionValues = implode('|', $cleanedOptionValues);

        $priceVenue = $this->calculatePriceVenue($this->date_from, $this->date_to, $this->time_from, $this->time_to, $this->area_id);

        $priceOptionsStringArray = $this->calculatePriceOptions($this->date_from, $this->date_to, $this->time_from, $this->time_to, $optionIds, $optionValues, $this->people);

        $cleanedArray = [];
        $optionIdMap = [];

        foreach ($priceOptionsStringArray['individualPrices'] as $item) {
            $optionId = $item['optionId'];
            if (!isset($optionIdMap[$optionId])) {
                $optionIdMap[$optionId] = $item;
            }
        }

        $cleanedArray['totalPrice'] = $priceOptionsStringArray['totalPrice'];
        $cleanedArray['individualPrices'] = array_values($optionIdMap);

        $priceOptionsArray = $cleanedArray['individualPrices'];
        $priceOptionsString = implode('|', array_map(function($item) {
            return $item['price'];
        }, $priceOptionsArray));

        // Calculate the total of all the option prices
        $valuesArray = explode('|', $priceOptionsString);
        $priceOptions = array_sum(array_map('floatval', $valuesArray));
        $calculatedPrice = $priceVenue + $priceOptions;

        $totalPrice = $calculatedPrice;

        // Save the new quote to the database
        Quote::create([
                'contact_id' => $this->contact_id,
                'status' => 'Draft',
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
                'calculated_price' => $calculatedPrice,
                'discount_type' => $this->discount_type,
                'discount' => $this->discount,
                'price' => $totalPrice,
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

    

    public function render()
    {
        $currentTenantId = Session::get('current_tenant_id');
        $contacts = Contact::where('tenant_id', $currentTenantId)->get();
        $venues = Venue::where('tenant_id', $currentTenantId)->get();
        $venueAreas = VenueArea::where('tenant_id', $currentTenantId)->get();
        $eventTypes = EventType::where('tenant_id', $currentTenantId)->get();

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


        // Convert the date strings to Carbon instances
        $dateFrom = Carbon::createFromFormat('d-m-Y', $this->date_from);
        $currentDate = $dateFrom;
        $currentDayOfWeek = $currentDate->format('D');

        // Get the seasons for the selected date and weekday
        $seasons = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek)->first();

        // Find the associated venue ID for the selected area
        $selectedArea = VenueArea::find($this->area_id);
        $selectedVenueId = optional($selectedArea->venue)->id;

        // Query the options based on the selected venue and the season with the highest priority
        $venueOptions = Option::orderBy('position')
            ->where(function ($query) use ($selectedVenueId) {
                $query->whereRaw('FIND_IN_SET(?, venue_ids) > 0', [$selectedVenueId]);
            })
            ->when($seasons, function ($query) use ($seasons) {
                return $query->whereRaw('FIND_IN_SET(?, season_ids) > 0', [$seasons->id]);
            })
            ->get();

        // Get the "All" season
        $allSeason = Season::getAllSeason();

        // Retrieve options associated with the "All" season
        $allSeasonOptions = $allSeason
            ? Option::orderBy('position')
                ->where(function ($query) use ($allSeason) {
                    $query->whereRaw('FIND_IN_SET(?, season_ids) > 0', [$allSeason->id]);
                })
                ->get()
            : collect();

        // Include options with always_included = 1
        $alwaysIncludedOptions = Option::where('always_included', 1)->get();
        foreach ($alwaysIncludedOptions as $option) {
            // Check if the option is not already selected to avoid duplicates
            if (!isset($this->selectedOptions[$option->id])) {
                $this->selectedOptions[$option->id] = $option->default_value; // Set the default value
            }
        }


        // Merge the two sets of options
        $this->options = $venueOptions->concat($allSeasonOptions)->unique('id')->sortBy('position');
        // Loop through the options and set the value for the specific logic option
        foreach ($this->options as $option) {
            if ($option->type === 'logic') {
                // Set the value for the specific logic option
                $option->value = $this->calculateLogicOptionValues($option->id);
                $this->selectedOptions[$option->id] = $option->value;
            }
        }

    }




}
