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

        // Initialize the total price
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
                            $currentDate, // Use the same date for both from and to when calculating for each day
                            $timeFrom,
                            $timeTo,
                            $optionId,
                            $people
                        );
                        $totalPrice += $optionTotalPrice;
                    }
                }
            }

            // Move to the next day
            $currentDate->addDay();
        }

        return $totalPrice;
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
        }  elseif ($optionType === 'logic') {
            // Handle the logic option type
            $logicOptionValue = $this->calculateLogicOptionValue($optionId, $people, $days, $hours);
            $price = $logicOptionValue * $quantity;
        }

        return $price;

    }

    public function calculateLogicOptionValue($optionId, $people, $days, $hours)
    {
        // Get the option from the database based on the option ID
        $option = Option::find($optionId);

        if (!$option) {
            // Handle the case where the option with the given ID is not found
            return 0; // or any default value
        }

        $values = explode('|', $option->values);

        // Split the logic field into individual conditions based on 'AND' or 'OR' operators
        $conditions = preg_split('/\s+(AND|OR)\s+/', $option->logic, -1, PREG_SPLIT_DELIM_CAPTURE);

        // Initialize a flag to track the overall result
        $overallResult = false;

        // Initialize a flag to track the logical operator ('AND' or 'OR')
        $currentOperator = null;

        foreach ($conditions as $condition) {
            if ($condition === 'AND' || $condition === 'OR') {
                // Set the current logical operator for the next condition
                $currentOperator = $condition;
            } else {
                // Parse the individual condition
                $parsedCondition = $this->parseCondition($condition, $people, $days, $hours);

                // Evaluate the condition and combine it with the overall result
                if ($currentOperator === 'AND') {
                    $overallResult = $overallResult && $parsedCondition;
                } elseif ($currentOperator === 'OR') {
                    $overallResult = $overallResult || $parsedCondition;
                } else {
                    // For the first condition, set the overall result directly
                    $overallResult = $parsedCondition;
                }
            }
        }

        // Calculate the final result based on the overall result
        $result = $overallResult ? $values[0] : $values[1];

        return $result;
    }

    private function parseCondition($condition, $people, $days, $hours)
    {
        // Parse the individual condition to extract field, operator, and value
        if (preg_match('/([a-zA-Z_]+)\s+(equals|not_equals|less_than|greater_than|less_than_or_equals|greater_than_or_equals)\s+["\']?([^"\']+)?["\']?/', $condition, $matches)) {
            $field = $matches[1];
            $operator = $matches[2];
            $value = $matches[3];

            // Evaluate the condition based on the field, operator, and value
            $fieldValue = $this->getFieldValue($field, $people, $days, $hours);

            switch ($operator) {
                case 'equals':
                    return $fieldValue == $value;
                case 'not_equals':
                    return $fieldValue != $value;
                case 'less_than':
                    return $fieldValue < $value;
                case 'greater_than':
                    return $fieldValue > $value;
                case 'less_than_or_equals':
                    return $fieldValue <= $value;
                case 'greater_than_or_equals':
                    return $fieldValue >= $value;
                default:
                    return false;
            }
        }

        return false;
    }

    private function getFieldValue($field, $people, $days, $hours)
    {
        // Map the field name to the corresponding variable
        switch ($field) {
            case 'people':
                return $people;
            case 'days':
                return $days;
            case 'hours':
                return $hours;
            default:
                return null; // Handle unknown field names as needed
        }
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

            // Include options with always_included = 1
            $alwaysIncludedOptions = Option::where('always_included', 1)->get();

            foreach ($alwaysIncludedOptions as $option) {
                if (!isset($this->selectedOptions[$option->id])) {
                    $this->selectedOptions[$option->id] = $option->default_value;
                }
            }

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
            ->when($selectedVenueId, function ($query) use ($selectedVenueId) {
                return $query->where('venue_id', $selectedVenueId);
            })
            ->when($seasons, function ($query) use ($seasons) {
                return $query->where('season_id', $seasons->id);
            })
            ->get();

        // Get the "All" season
        $allSeason = Season::getAllSeason();

        // Retrieve options associated with the "All" season
        $allSeasonOptions = $allSeason
            ? $allSeason->options()->orderBy('position')->get()
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
    }



}
