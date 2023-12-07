<?php

namespace App\Http\Livewire\Quote;

use Livewire\Component;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\Option;
use App\Models\VenueArea;
use App\Models\Venue;
use App\Models\Price;
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
    public $discount;
    public $price;
    public $price_venue;
    public $price_options;
    public $selectedVenueId;
    public $people;
    public $eventName = '';
    public $eventTypes = [];
    public $logicOptionValue;
    public $time_ranges = [];
    public $buffer_time_before;
    public $buffer_time_after;
    public $buffer_time_unit;


    public $edit_mode = false;

    protected $listeners = [
        'delete_quote' => 'deleteQuote',
    ];

    public function submit()
    {
        
        $this->eventTypes = [];

        $this->validateQuoteData();
        $newQuoteNumber = $this->getNewQuoteNumber();

        if($this->selectedOptions) {

        // Convert selected options to a comma-separated string format
        $optionIdsIm = implode('|', array_keys($this->selectedOptions));

        Log::info("-----optionIdsIm". json_encode($optionIdsIm));

        $optionValuesIm = implode('|', array_values($this->selectedOptions));

        $optionIds = explode('|', $optionIdsIm);

        $optionValues = explode('|', $optionValuesIm);

        $cleanedOptionIds = [];
        $cleanedOptionValues = [];

        foreach ($optionValues as $index => $value) {
            if ($value !== '' && $value != 0) {
                $cleanedOptionIds[] = $optionIds[$index];
                $cleanedOptionValues[] = $value;
            }
        }

        $optionIds = implode('|', $cleanedOptionIds);
        $optionValues = implode('|', $cleanedOptionValues);

        } 

        $timeFromArray = [];
        $timeToArray = [];

        foreach ($this->time_ranges as $time_range) {
            $timeFromArray[] = $time_range['time_from'];
            $timeToArray[] = $time_range['time_to'];
        }

        $timeFrom = implode('|', $timeFromArray);
        $timeTo = implode('|', $timeToArray);

        $priceBufferVenue = $this->calculateBufferPriceVenue($this->buffer_time_before, $this->buffer_time_after, $this->buffer_time_unit, $this->area_id);

        Log::info('Buffer Price Venue: ' . $priceBufferVenue);

        $priceVenue = $this->calculatePriceVenue($this->date_from, $this->date_to, $timeFrom, $timeTo, $this->area_id);

        Log::info('Price Venue: ' . $priceVenue);

        $priceVenue = $priceVenue + $priceBufferVenue;

        if($this->selectedOptions) {

        // Calculate regular and buffer prices for options
        $priceBufferOptionsStringArray = $this->calculateBufferPriceOptions($this->date_from, $this->date_to, $optionIds, $optionValues, $this->people, $this->buffer_time_before, $this->buffer_time_after, $this->buffer_time_unit);

        $priceOptionsStringArray = $this->calculatePriceOptions($this->date_from, $this->date_to, $timeFrom, $timeTo, $optionIds, $optionValues, $this->people);

        $cleanedArray = [];
        $optionIdMap = [];

        // Loop through the regular option prices
        foreach ($priceOptionsStringArray['individualPrices'] as $item) {

            Log::info('Option Price for ID ' . $item['optionId'] . ': ' . $item['price']);

            $optionId = $item['optionId'];
            $optionIdMap[$optionId] = $item; // Add or update the item
        }

        // Add buffer prices to the regular prices
        foreach ($priceBufferOptionsStringArray['individualPrices'] as $bufferItem) {

            Log::info('Buffer Price for ID ' . $bufferItem['optionId'] . ': ' . $bufferItem['price']);

            $optionId = $bufferItem['optionId'];
            $bufferPrice = $bufferItem['price'];

            if (isset($optionIdMap[$optionId])) {
                $optionIdMap[$optionId]['price'] += $bufferPrice;
            } else {
                $optionIdMap[$optionId] = $bufferItem; // Add the buffer item if it doesn't exist in the regular prices
            }

        }

        // Update the total price
        $cleanedArray['totalPrice'] = $priceOptionsStringArray['totalPrice'] + $priceBufferOptionsStringArray['totalPrice'];
        $cleanedArray['individualPrices'] = array_values($optionIdMap);

        // Your existing code to handle the price options string and calculate the final prices
        $priceOptionsArray = $cleanedArray['individualPrices'];
        $priceOptionsString = implode('|', array_map(function($item) {
            return $item['price'];
        }, $priceOptionsArray));


        $valuesArray = explode('|', $priceOptionsString);
        $priceOptions = array_sum(array_map('floatval', $valuesArray));

        } else {
            $priceOptions = 0;
            $priceOptionsString = NULL;
            $optionIds = NULL;
            $optionValues = NULL;
        }
        $calculatedPrice = $priceVenue + $priceOptions;

        try {
            // Apply discount to the calculated price
            $totalPrice = $this->applyDiscount($calculatedPrice, $this->discount);
        } catch (\Exception $e) {
            // Handle exceptions related to discount parsing, e.g., flash a message to the session
            session()->flash('error', $e->getMessage());
            return;
        }

        // Save the new quote to the database
        Quote::create([
                'contact_id' => $this->contact_id,
                'status' => 'Draft',
                'version' => '1',
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'time_from' => $timeFrom,
                'time_to' => $timeTo,
                'area_id' => $this->area_id,
                'event_type' => $this->event_type,
                'event_name' => $this->eventName,
                'people' => $this->people,
                'quote_number' => $newQuoteNumber, // Assign the new quote number
                'calculated_price' => $calculatedPrice,
                'discount' => $this->discount,
                'price' => $totalPrice,
                'price_venue' => $priceVenue,
                'price_options' => $priceOptionsString,
                'options_ids' => $optionIds,
                'options_values' => $optionValues,
                'buffer_time_before' => $this->buffer_time_before,
                'buffer_time_after' => $this->buffer_time_after,
                'buffer_time_unit' => $this->buffer_time_unit,
        ]);

        // Update the current quote number in the system_information table
        DB::table('system_information')->where('key', 'current_quote_number')->update(['value' => $newQuoteNumber]);

        // Emit an event to notify that the quote was created successfully
        $this->emit('success', 'Quote successfully added');

        // Reset the form fields
        $this->reset(['contact_id', 'status', 'version', 'date_from', 'date_to', 'time_from', 'time_to', 'area_id', 'event_type','event_name', 'edit_mode', 'quoteId', 'calculated_price', 'people', 'discount', 'price', 'price_venue', 'price_options', 'options_ids' , 'options_values']);
    }

    private function validateQuoteData()
    {
        $this->validate([
            'contact_id' => 'required',
            'area_id' => 'required',
            'event_type' => 'required',
            'people' => 'required',
            'time_ranges.*.time_from' => 'required',
            'time_ranges.*.time_to' => 'required',
        ]);
    }

    private function getNewQuoteNumber()
    {
        $currentQuoteNumber = DB::table('system_information')->where('key', 'current_quote_number')->value('value');
        return $currentQuoteNumber ? $currentQuoteNumber + 1 : 1;
    }

    public function updatedDateFrom($value)
    {
        $this->calculateTimeRanges();
    }

    public function updatedDateTo($value)
    {
        $this->calculateTimeRanges();
    }

    private function calculateTimeRanges()
    {
        if (!empty($this->date_from) && !empty($this->date_to)) {
            $start = \Carbon\Carbon::createFromFormat('d-m-Y', $this->date_from);
            $end = \Carbon\Carbon::createFromFormat('d-m-Y', $this->date_to);

            // Initialize an array to store time ranges
            $this->time_ranges = [];

            // Loop through each day and add time ranges
            while ($start->lte($end)) {
                $this->time_ranges[$start->format('d-m-Y')] = [
                    'time_from' => '',
                    'time_to' => '',
                ];

                $start->addDay();
            }
        }
    }

    public function updateSelectedOption($optionId, $value)
    {
        Log::info("updateSelectedOption". json_encode($this->selectedOptions));
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
        if ($option && in_array($option->type, ['yes_no', 'number', 'radio', 'checkbox', 'logic', 'always'])) {
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
            ->where('extra_tier_type', 'like', '%event%')
            ->first();
    }

    private function getOptionBufferPriceForSeason($optionId, $seasonId)
    {
        return Option::find($optionId)->prices()
            ->where('type', 'option')
            ->where('season_id', $seasonId)
            ->where(function($query) {
                $query->where('extra_tier_type', 'like', '%buffer_before%')
                      ->orWhere('extra_tier_type', 'like', '%buffer_after%');
            })
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
    private function calculateNumberOfHours($dateFrom, $timeFrom, $dateTo, $timeTo) //remove the date eventually
    {
        // Split the time ranges into arrays
        $timeFromArray = explode('|', $timeFrom);
        $timeToArray = explode('|', $timeTo);

        // Initialize an array to store the total hours for each day
        $totalHoursPerDay = [];

        // Loop through each day and calculate the total hours
        for ($i = 0; $i < count($timeFromArray); $i++) {
            // Split the time for the current day
            $hoursFrom = explode(':', $timeFromArray[$i]);
            $hoursTo = explode(':', $timeToArray[$i]);

            // Calculate the hours and minutes for the current day
            $fromHours = intval($hoursFrom[0]);
            $fromMinutes = intval($hoursFrom[1]);
            $toHours = intval($hoursTo[0]);
            $toMinutes = intval($hoursTo[1]);

            // Calculate the difference in hours for the current day
            $hoursDifference = ($toHours - $fromHours) + ($toMinutes - $fromMinutes) / 60;

            // Store the total hours for the current day
            $totalHoursPerDay[] = $hoursDifference;
        }

        // Calculate the sum of total hours for all days
        $totalHours = array_sum($totalHoursPerDay);
        return $totalHours;
    }

    private function calculateBufferPriceVenue($bufferBefore, $bufferAfter, $bufferUnit, $areaId) {
        // 1. Get the associated venue and area
        $venue = Venue::whereHas('areas', function ($query) use ($areaId) {
            $query->where('id', $areaId);
        })->first();
        $area = VenueArea::find($areaId);

        // 2. Determine the season
        $formattedDate = Carbon::createFromFormat('d-m-Y', $this->date_from)->format('Y-m-d');
        $weekday = Carbon::createFromFormat('d-m-Y', $this->date_from)->format('D');
        $currentTenantId = Session::get('current_tenant_id');
        $seasons = Season::where('tenant_id', $currentTenantId)
            ->where(function ($query) use ($formattedDate, $weekday) {
                $query->where('date_from', '<=', $formattedDate)
                    ->where('date_to', '>=', $formattedDate)
                    ->where(function ($query) use ($weekday) {
                        $query->whereJsonContains('weekdays', [$weekday])
                            ->orWhere('weekdays', null);
                    });
            })->orderBy('priority', 'desc')->get();

        // 3. Calculate buffer prices
        $totalBufferPrice = 0;
        foreach ($seasons as $season) {
            $prices = $this->fetchBufferPricesVenue($venue->id, $areaId, $season->id, $bufferUnit);

            foreach ($prices as $price) {
                $totalBufferPrice += $this->applyBufferPriceLogic($price, $bufferBefore, $bufferAfter, $bufferUnit);
            }
        }

        return $totalBufferPrice;
    }

    private function fetchBufferPricesVenue($venueId, $areaId, $seasonId, $bufferUnit) {
    // Fetch prices with extra_tier_type containing buffer_before or buffer_after
    // and type value "venue" or "area" associated with the current season
    return Price::where(function ($query) use ($venueId, $areaId) {
            $query->where(function ($subQuery) use ($venueId) {
                $subQuery->where('type', 'venue')
                         ->where('venue_id', $venueId);
            })->orWhere(function ($subQuery) use ($areaId) {
                $subQuery->where('type', 'area')
                         ->where('area_id', $areaId);
            });
        })
        ->where('season_id', $seasonId)
        ->where(function ($query) {
            $query->where('extra_tier_type', 'like', '%buffer_before%')
                  ->orWhere('extra_tier_type', 'like', '%buffer_after%');
        })
        ->get();
}

    private function applyBufferPriceLogic($price, $bufferBefore, $bufferAfter, $bufferUnit) {
        // Convert buffer times based on unit and multiplier type
        if ($bufferUnit == 'hours') {
            if ($price->multiplier == 'daily') {
                // Convert buffer hours to days considering a day as 8 hours
                $bufferDaysBefore = ceil($bufferBefore / 8);
                $bufferDaysAfter = ceil($bufferAfter / 8);
                return ($bufferDaysBefore + $bufferDaysAfter) * $price->price;
            } elseif ($price->multiplier == 'hourly') {
                // If both buffer and price are in hours, calculate directly
                return ($bufferBefore + $bufferAfter) * $price->price;
            }
        } elseif ($bufferUnit == 'days') {
            if ($price->multiplier == 'daily') {
                // If both buffer and price are in days, calculate directly
                return ($bufferBefore + $bufferAfter) * $price->price;
            } elseif ($price->multiplier == 'hourly') {
                // Convert buffer days to hours considering a day as 8 hours
                $bufferHoursBefore = $bufferBefore * 8;
                $bufferHoursAfter = $bufferAfter * 8;
                return ($bufferHoursBefore + $bufferHoursAfter) * $price->price;
            }
        }

        // Default return if none of the conditions above are met
        return 0;
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

        while ($currentDate->lte($dateToC)) {
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
                    ->where('extra_tier_type', 'like', '%event%')
                    ->first();

                // Check if there is a price associated with the venue for this season
                $venuePrice = $venue->prices()
                    ->where('type', 'venue')
                    ->where('season_id', $season->id)
                    ->where('extra_tier_type', 'like', '%event%')
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
                        $days = $this->calculateNumberOfDays($dateFrom, $dateTo);
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

    private function calculateOptionBufferPrice($optionType, $optionValue, $optionPrice, $multiplierType, $multiplierValue, $bufferTimeBefore, $bufferTimeAfter, $bufferTimeUnit, $optionId, $people)
    {
        $price = 0;

        // Calculate the total buffer time in hours
        $totalBufferHours = 0;
        if ($bufferTimeUnit == 'days') {
            $totalBufferHours = ($bufferTimeBefore + $bufferTimeAfter) * 8;
        } else { // Assuming the unit is hours
            $totalBufferHours = $bufferTimeBefore + $bufferTimeAfter;
        }

        // Calculate days and hours from the total buffer hours
        $days = ceil($totalBufferHours / 8);
        $hours = $totalBufferHours;

        Log::info('');
        Log::info('Buffer Hours ' . $hours);
        Log::info('');


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

        Log::info('Type Option ' . $optionId . ': ' . $optionType);
        Log::info('Multiplier Type for Option ' . $optionId . ': ' . $multiplierType);
        Log::info('Price for Option ' . $optionId . ': ' . $price);

        return $price;
    }

    private function getDefaultOptionValue($optionId)
    {
        $option = Option::find($optionId);

        // Check if the option exists and has a default value
        if ($option && isset($option->default_value)) {
            return $option->default_value;
        }

        return null; // Return null if no default value is set or option doesn't exist
    }

    private function calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $quantity, $optionId, $people, $hours, $days)
    {
        $price = 0;

        if ($optionType === 'yes_no') {
            $price = $optionValue === 'yes' ? $multiplierValue * $quantity : 0;
        } elseif ($optionType === 'always') {
            $price = $multiplierValue * $quantity * $this->getDefaultOptionValue($optionId);
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

        foreach ($this->time_ranges as $time_range) {
            $timeFromArray[] = $time_range['time_from'];
            $timeToArray[] = $time_range['time_to'];
        }

        $timeFrom = implode('|', $timeFromArray);
        $timeTo = implode('|', $timeToArray);

        $days = $this->calculateNumberOfDays($dateFrom, $dateTo);
        $hours = $this->calculateNumberOfHours($dateFrom, $timeFrom, $dateTo, $timeTo);

        $optionValues = explode('|', $this->getOptionValues($optionId));
        $logicOption = $this->getLogicOptionDetails($optionId,  $this->people, $hours, $days);
        $logicOptionValue = $logicOption ? $optionValues[0] : $optionValues[1];
        return $logicOptionValue;
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
        $logicExpression = str_replace('greater_than_or_equals', '>=', $logicExpression);
        $logicExpression = str_replace('less_than_or_equals', '<=', $logicExpression);
        $logicExpression = str_replace('not_equals', '!=', $logicExpression);
        $logicExpression = str_replace('equals', '==', $logicExpression);
        $logicExpression = str_replace('greater_than', '>', $logicExpression);
        $logicExpression = str_replace('less_than', '<', $logicExpression);
        $logicExpression = str_replace('"', '', $logicExpression);


        // Split the logic expression by "OR" operators
        $orConditions = explode(' OR ', $logicExpression);

        $result = false;

        foreach ($orConditions as $orCondition) {
            // Split each OR condition by "AND" operators
            $andConditions = explode(' AND ', $orCondition);

            $orResult = true; // Initialize the OR result as true

            foreach ($andConditions as $andCondition) {
                // Split each condition into field, operator, and value
                $parts = preg_split('/(<=|>=|==|!=|<|>)/', $andCondition, -1, PREG_SPLIT_DELIM_CAPTURE);
                if (count($parts) === 3) {
                    list($field, $operator, $value) = array_map('trim', $parts);

                    // Evaluate the condition
                    switch ($operator) {
                        case '<':
                            $andResult = ($field < $value);
                            break;
                        case '<=':
                            $andResult = ($field <= $value);
                            break;
                        case '>':
                            $andResult = ($field > $value);
                            break;
                        case '>=':
                            $andResult = ($field >= $value);
                            break;
                        case '==':
                            $andResult = ($field == $value);
                            break;
                        case '!=':
                            $andResult = ($field != $value);
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

                
                Log::info("-----optionIds". json_encode($optionIds));

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

    public function calculateBufferPriceOptions($dateFrom, $dateTo, $optionIds, $optionValues, $people, $bufferTimeBefore, $bufferTimeAfter, $bufferTimeUnit)
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
                    $optionPrice = $this->getOptionBufferPriceForSeason($optionId, $season->id);
                    $optionType = $this->getOptionType($optionId);
                    if ($optionPrice) {
                        $multiplierValue = (float)$optionPrice->price;
                        $optionTotalPrice = $this->calculateOptionBufferPrice(
                            $optionType,
                            $optionValue,
                            $optionPrice,
                            $optionPrice->multiplier,
                            $multiplierValue,
                            $bufferTimeBefore, 
                            $bufferTimeAfter,
                            $bufferTimeUnit,
                            $optionId,
                            $people
                        );

                        Log::info('optionPrice ' . $optionId . ': ' . $optionPrice);

                        Log::info('Single Buffer Price for Option ' . $optionId . ': ' . $optionTotalPrice);
                        Log::info('');

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
        $currentTenantId = Session::get('current_tenant_id');
        $this->eventTypes = EventType::where('name', 'like', '%' . $this->eventName . '%')
                                        ->where('tenant_id', $currentTenantId)
                                        ->get();
    }

    private function loadOptions()
    {

        $currentTenantId = Session::get('current_tenant_id');
        
        // Check if the required fields are set
        if (!$this->date_from || !$this->area_id) {
            $this->options = collect(); // No options to display if date is not set
            return;
        }

        // Convert the date strings to Carbon instances
        $dateFrom = Carbon::createFromFormat('d-m-Y', $this->date_from);
        $currentDate = $dateFrom;
        $currentDayOfWeek = $currentDate->format('D');

        // Get the seasons for the selected date and weekday
        $seasons = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek)->first();

        // Get the "All" season ID
        $allSeasonId = Season::getAllSeason()->where('tenant_id', $currentTenantId)->first()->id ?? null;

        // Find the associated venue ID for the selected area
        $selectedAreaId = VenueArea::find($this->area_id);
        $selectedVenueId = optional($selectedAreaId)->venue->id ?? null;
        $selectedEventTypeId = EventType::find($this->event_type);

        $optionsQuery = Option::orderBy('position')
        ->where('tenant_id', $currentTenantId)
        ->where(function ($query) use ($seasons, $allSeasonId) {
            $query->where(function ($q) use ($seasons) {
                $q->whereRaw('FIND_IN_SET(?, season_ids) > 0', [$seasons->id ?? 0]);
            })->orWhere(function ($q) use ($allSeasonId) {
                $q->whereRaw('FIND_IN_SET(?, season_ids) > 0', [$allSeasonId]);
            });
        });

        

        // Refine additional filters
        /*if ($selectedVenueId) {
            $optionsQuery->where(function ($query) use ($selectedVenueId) {
                $query->whereRaw('FIND_IN_SET(?, venue_ids) > 0', [$selectedVenueId])
                        ->orWhereNull('venue_ids');
            });
        }*/
        if ($selectedAreaId) {
            $optionsQuery->where(function ($query) use ($selectedAreaId) {
                $query->whereRaw('FIND_IN_SET(?, area_ids) > 0', [$selectedAreaId->id])
                        ->orWhereNull('area_ids');
            });
        }

        

        if ($selectedEventTypeId) {
            $optionsQuery->where(function ($query) use ($selectedEventTypeId) {
                $query->whereRaw('FIND_IN_SET(?, eventtype_ids) > 0', [$selectedEventTypeId->id])
                        ->orWhereNull('eventtype_ids');
            });
        }
        
            
        $this->options = $optionsQuery->get();
        // Set values for specific logic options and default values
        foreach ($this->options as $option) {
            if ($option->type === 'logic') {
                $option->value = $this->calculateLogicOptionValues($option->id);
            }

            
            Log::info("loadOptions". json_encode($this->options));
            // $this->selectedOptions[$option->id] = $option->value ?? $option->default_value;
        }
    }

    private function applyDiscount($calculatedPrice, $discount)
    {
        // Remove whitespace and parse discount
        $discount = trim($discount);

        // Check if the discount is a percentage
        if (str_ends_with($discount, '%')) {
            $percentage = rtrim($discount, '%');
            if (is_numeric($percentage)) {
                // Calculate the discount as a percentage of the calculated price
                $discountAmount = ($percentage / 100) * $calculatedPrice;
            } else {
                // Handle invalid percentage format
                throw new \Exception("Invalid discount percentage format");
            }
        } elseif (is_numeric($discount)) {
            // If the discount is a numeric value, treat it as a flat amount
            $discountAmount = $discount;
        } else {
            // Handle invalid discount format
            throw new \Exception("Invalid discount format");
        }

        // Subtract the discount amount from the calculated price
        return max($calculatedPrice - $discountAmount, 0); // Ensure the total doesn't go below 0
    }





}
