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
use App\Models\Tenant;
use App\Models\EventType;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;


class AddQuoteModal extends Component
{
    public $stepperIndex = 1;
    public $contact_id;
    public $status;
    public $version;
    public $date_from;
    public $date_to;
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
    public $eventTypes = [];
    public $logicOptionValue;
    public $time_ranges = [];
    public $buffer_time_before;
    public $buffer_time_after;
    public $buffer_time_unit = "days";


    public $filteredContacts = [];
    public $edit_mode = false;

    protected $listeners = [
        'create_quote' => 'createQuote',
        'delete_quote' => 'deleteQuote',
        'update_time_range' => 'updateTimeRange',
        'update_date_range' => 'updateDateRange',
        'set_stepper_index' => 'setStepperIndex',
        'update_filtered_contact_list' => 'updateFilteredContactList',
    ];

    public function updateFilteredContactList() {
        $currentTenantId = Session::get('current_tenant_id');

        // Code for parent tenant
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId; // self and child tenant ids.
        
        // filtered contacts, venues and venue areas
        $this->filteredContacts = Contact::whereIn('tenant_id', $tenantIds)->get();
    }

    public function createQuote() {
        $this->updateFilteredContactList();
    }

    public function setStepperIndex($index) {
        $this->stepperIndex = $index;
        $this->dispatchBrowserEvent('stepper-index-updated', ['index' => $index]);
    }

    public function submit()
    {
        
        $this->eventTypes = [];

        $this->validateQuoteData();

        if($this->selectedOptions) {

            // Convert selected options to a comma-separated string format
            $optionIdsIm = implode('|', array_keys($this->selectedOptions));

            Log::info("-----optionIdsIm". json_encode($optionIdsIm));

            $optionValuesIm = implode('|', array_values($this->selectedOptions));

            $optionIds = explode('|', $optionIdsIm);

            $optionValues = explode('|', $optionValuesIm);

            $cleanedOptionIds = [];
            $cleanedOptionValues = [];
            $cleanedOptionTenantIds = [];
           
            foreach ($optionValues as $index => $value) {
                Log::info("-----optionValues loop". $index . "-". $value);
                if ($value !== '' && $value != 0 && $value !== 'no') {
                    $cleanedOptionIds[] = $optionIds[$index];
                    $cleanedOptionValues[] = $value;
                    $cleanedOptionTenantIds[] = Option::find($optionIds[$index])->tenant->id;
                }
            }

            $optionIds = implode('|', $cleanedOptionIds);
            $optionValues = implode('|', $cleanedOptionValues);
            $optionTenantIds = implode('|', $cleanedOptionTenantIds);
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

        // selected area's tenant priceVenue.
        $priceVenue = $priceVenue + $priceBufferVenue;

        $mainPriceOptions = 0;
        $mainTenantId = VenueArea::find($this->area_id)->tenant_id;
        $mainPriceOptionsString = '';
        $mainOptionIds = [];
        $mainOptionValues = [];

        if($this->selectedOptions) {


            // Calculate regular and buffer prices for options
            $priceBufferOptionsStringArray = $this->calculateBufferPriceOptions($this->date_from, $this->date_to, $optionIds, $optionValues, $optionTenantIds, $this->people, $this->buffer_time_before, $this->buffer_time_after, $this->buffer_time_unit);

            $priceOptionsStringArray = $this->calculatePriceOptions($this->date_from, $this->date_to, $timeFrom, $timeTo, $optionIds, $optionValues, $optionTenantIds, $this->people);


            // Loop through the regular option prices
            foreach ($priceOptionsStringArray as $tenantId => $item) {


                
                $newQuoteNumber = $this->getNewQuoteNumber();

                Log::info('Option Price for ID ' . json_encode($item['totalPrice']) . ': ' . json_encode($item['individualPrices']));

                 // Update the total price
                 $item['totalPrice'] = $item['totalPrice'] + $priceBufferOptionsStringArray[$tenantId]['totalPrice']; 
                
                foreach($item['individualPrices'] as $optionId => $optionTotalPrice) {
                    $item['individualPrices'][$optionId] += $priceBufferOptionsStringArray[$tenantId]['individualPrices'][$optionId];
                } 

                // Your existing code to handle the price options string and calculate the final prices
                $priceOptionsString = implode('|', array_values($item['individualPrices']));

                $optionIds = [];
                $optionValues = [];
                foreach( $item['individualPrices'] as $optionId => $optionTotalPrice) {
                    $optionIds[] = $optionId;
                    $optionValues[] = $item["optionValues"][$optionId];
                } 


                $priceOptions = array_sum(array_map('floatval', array_values($item['individualPrices'])));
                
                $calculatedPrice = $priceOptions;

                if($mainTenantId === $tenantId) {
                    $mainPriceOptions = $calculatedPrice;
                    $mainTenantId = $tenantId;
                    $mainPriceOptionsString = $priceOptionsString;
                    $mainOptionIds = $optionIds;
                    $mainOptionValues = $optionValues;
                }

                else {

                    try {
                        // Apply discount to the calculated price
                        $totalPrice = $this->applyDiscount($calculatedPrice, 0);
                    } catch (\Exception $e) {
                        // Handle exceptions related to discount parsing, e.g., flash a message to the session
                        session()->flash('error', $e->getMessage());
                        return;
                    }
    
                    Quote::create([
                        'contact_id' => $this->contact_id,
                        'status' => 'Draft',
                        'version' => '1',
                        'date_from' => $this->date_from,
                        'date_to' => $this->date_to,
                        'time_from' => $timeFrom,
                        'time_to' => $timeTo,
                        'area_id' => 0,
                        'event_type' => $this->event_type,
                        'event_name' => $this->event_name,
                        'people' => $this->people,
                        'quote_number' => $newQuoteNumber, // Assign the new quote number
                        'calculated_price' => $calculatedPrice,
                        'discount' => 0,
                        'price' => $totalPrice,
                        'price_venue' => 0,
                        'price_options' => $priceOptionsString,
                        'options_ids' => implode("|", $optionIds),
                        'options_values' => implode("|", $optionValues),
                        'buffer_time_before' => $this->buffer_time_before,
                        'buffer_time_after' => $this->buffer_time_after,
                        'buffer_time_unit' => $this->buffer_time_unit,
                        'tenant_id' => $tenantId
                    ]);
                    DB::table('system_information')->where('key', 'current_quote_number')->update(['value' => $newQuoteNumber]);
                }

            }

        } 

        $newQuoteNumber = $this->getNewQuoteNumber();

        $calculatedPrice = $priceVenue + $mainPriceOptions;
        $totalPrice = $this->applyDiscount($calculatedPrice, $this->discount);

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
            'event_name' => $this->event_name,
            'people' => $this->people,
            'quote_number' => $newQuoteNumber, // Assign the new quote number
            'calculated_price' => $calculatedPrice,
            'discount' => $this->discount,
            'price' => $totalPrice,
            'price_venue' => $priceVenue,
            'price_options' => $mainPriceOptionsString,
            'options_ids' => implode("|", $mainOptionIds),
            'options_values' => implode("|", $mainOptionValues),
            'buffer_time_before' => $this->buffer_time_before,
            'buffer_time_after' => $this->buffer_time_after,
            'buffer_time_unit' => $this->buffer_time_unit,
            'tenant_id' => $mainTenantId
        ]);
        DB::table('system_information')->where('key', 'current_quote_number')->update(['value' => $newQuoteNumber]);
       
        // Emit an event to notify that the quote was created successfully
        $this->emit('success', 'Quote successfully added');

        // Reset the form fields
        $this->reset([
            'stepperIndex',
            'contact_id',
            'status', 
            'version', 
            'date_from', 
            'date_to', 
            'area_id', 
            'event_type',
            'event_name', 
            'edit_mode', 
            'quoteId', 
            'calculated_price', 
            'people', 
            'discount', 
            'price', 
            'price_venue', 
            'price_options', 
            'options_ids' , 
            'options_values',
            'time_ranges'
        ]);
    }

    public function isValidRange() {
        return false;
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
            'discount' => 'required|integer',
        ]);
    }

    private function getNewQuoteNumber()
    {
        $currentQuoteNumber = DB::table('system_information')->where('key', 'current_quote_number')->value('value');
        return $currentQuoteNumber ? $currentQuoteNumber + 1 : 1;
    }

    public function updatedEventType($value) {
        $selectedEvent = EventType::find($value);
        if($selectedEvent) $this->buffer_time_unit = $selectedEvent->duration_type;
        $this->calculateTimeRanges();
        $this->selectedVenueId = null;
        $this->area_id = null;
    }

    public function updatedBufferTimeUnit($value) {
        $selectedEvent = EventType::find($this->event_type);
        $this->dispatchBrowserEvent('buffer-time-unit-updated', ['value' => $value, 'selectedEvent' => $selectedEvent]);
    }

    public function updateDateRange($range) {
        $this->date_from = $range[0];
        $this->date_to = $range[1];
        $this->calculateTimeRanges();
    }

    public function updateTimeRange($data) {
        $index = $data["index"];
        $date = $data["date"];
        $range = $data["values"];
        $this->time_ranges[$date] = [
            'time_from' => $range[0],
            'time_to' => $range[1],
        ];
    }


    private function calculateTimeRanges()
    {
        if (!empty($this->date_from) && !empty($this->date_to)) {

            $selectedEvent = EventType::find($this->event_type);

            $time_from = '00:00';
            $time_to = '23:30';

            if($selectedEvent) {
                $time_from = $selectedEvent->opening_time;
                $time_to = $selectedEvent->closing_time;
            }

            $start = \Carbon\Carbon::createFromFormat('d-m-Y', $this->date_from);
            $end = \Carbon\Carbon::createFromFormat('d-m-Y', $this->date_to);

            // Initialize an array to store time ranges
            $this->time_ranges = [];

            // Loop through each day and add time ranges
            while ($start->lte($end)) {
                $this->time_ranges[$start->format('d-m-Y')] = [
                    'time_from' => $time_from,
                    'time_to' => $time_to,
                ];

                $start->addDay();
            }
            $this->dispatchBrowserEvent('date-range-updated', 
                [
                    'timeRanges' => $this->time_ranges,
                    'selectedEvent' => $selectedEvent
                ]);
        }
    }

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

        $selectedArea = VenueArea::find($this->area_id);

        $tenantIds = [];
        if($selectedArea) {
            $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
            $tenantIds[] = $currentTenantId;
        }

        // Query for seasons that match the date, weekday, and tenant_id
        return Season::whereIn('tenant_id', $tenantIds)
            ->where(function ($query) use ($date, $weekday) {
                $query->where(DB::raw('STR_TO_DATE(date_from, "%d-%m-%Y")'), '<=', $date)
                    ->where(DB::raw('STR_TO_DATE(date_to, "%d-%m-%Y")'), '>=', $date)
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

//    private function getOptionPriceForSeason($optionId, $seasonId)
//     {
//         return Option::find($optionId)->prices()
//             ->where('type', 'option')
//             ->where('season_id', $seasonId)
//             ->where('extra_tier_type', 'like', '%event%')
//             ->first();
//     }

//     private function getOptionBufferPriceForSeason($optionId, $seasonId)
//     {
//         return Option::find($optionId)->prices()
//             ->where('type', 'option')
//             ->where('season_id', $seasonId)
//             ->where(function($query) {
//                 $query->where('extra_tier_type', 'like', '%buffer_before%')
//                       ->orWhere('extra_tier_type', 'like', '%buffer_after%');
//             })
//             ->first();
//     }

    private function getOptionPricesForSeason($optionId, $seasonId)
    {
        return Option::find($optionId)->prices()
            ->where('type', 'option')
            ->where('season_id', $seasonId)
            ->where('extra_tier_type', 'like', '%event%')
            ->where(function($query) {
                $query->where('area_id', 'like', $this->area_id)
                      ->orWhere('area_id', null);
            })
            ->get();
    }

    private function getOptionBufferPricesForSeason($optionId, $seasonId)
    {
        return Option::find($optionId)->prices()
            ->where('type', 'option')
            ->where('season_id', $seasonId)
            ->where(function($query) {
                $query->where('extra_tier_type', 'like', '%buffer_before%')
                      ->orWhere('extra_tier_type', 'like', '%buffer_after%');
            })
            ->where(function($query) {
                $query->where('area_id', 'like', $this->area_id)
                      ->orWhere('area_id', null);
            })
            ->get();
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
       // Code for parent tenant
       $tenant = Tenant::find($currentTenantId);
       $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
       $tenantIds[] = $currentTenantId; // self and child tenant ids.


        $seasons = Season::whereIn('tenant_id', $tenantIds)
            ->where(function ($query) use ($formattedDate, $weekday) {
                $query->where(DB::raw('STR_TO_DATE(date_from, "%d-%m-%Y")'), '<=', Carbon::createFromFormat('d-m-Y', $this->date_from))
                    ->where(DB::raw('STR_TO_DATE(date_to, "%d-%m-%Y")'), '>=', Carbon::createFromFormat('d-m-Y', $this->date_from))
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
                $bufferDaysBefore = ceil(intval($bufferBefore) / 8);
                $bufferDaysAfter = ceil(intval($bufferAfter) / 8);
                return ($bufferDaysBefore + $bufferDaysAfter) * $price->price;
            } elseif ($price->multiplier == 'hourly') {
                // If both buffer and price are in hours, calculate directly
                return (intval($bufferBefore) + intval($bufferAfter)) * $price->price;
            }
        } elseif ($bufferUnit == 'days') {
            if ($price->multiplier == 'daily') {
                // If both buffer and price are in days, calculate directly
                return (intval($bufferBefore) + intval($bufferAfter)) * $price->price;
            } elseif ($price->multiplier == 'hourly') {
                // Convert buffer days to hours considering a day as 8 hours
                $bufferHoursBefore = intval($bufferBefore) * 8;
                $bufferHoursAfter = intval($bufferAfter) * 8;
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
                        $days = $this->calculateNumberOfDays($dateFromC, $dateToC);
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

    private function calculateOptionPrice($optionType, $optionValue, $optionPrice, $multiplierType, $x, $multiplierValue, $dateFrom, $dateTo, $timeFrom, $timeTo, $optionId, $people)
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

            case 'every_x_p':
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, 1, $optionId, $people, $hours, $days);
                break;
            case 'every_x_d':
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $days / $x, $optionId, $people, $hours, $days);
                break;
            case 'every_x_h':
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $hours / $x, $optionId, $people, $hours, $days);
                break;
        }
        if (str_ends_with($multiplierType, '_pp')) {
            $price *= $people;
        }

        if (str_ends_with($multiplierType, '_x_p')) {
            $price *= ($people / $x);
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

        Log::info('Buffer Hours ' . $hours);


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


    public function calculatePriceOptions($dateFrom, $dateTo, $timeFrom, $timeTo, $optionIds, $optionValues, $optionTenantIds, $people)
    {

        // Convert the date strings to Carbon instances
        $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom);
        $dateTo = Carbon::createFromFormat('d-m-Y', $dateTo);

        // Initialize the total price for options
        // $totalPrice = [];

        // Iterate through each day in the date range
        $currentDate = $dateFrom->copy();

        // $individualPrices = [];

        $pricesMap = [];

        while ($currentDate->lte($dateTo)) {
            // Get the day of the week for the current date (e.g., 'Mon')
            $currentDayOfWeek = $currentDate->format('D');

            // Get all seasons for the current date
            $matchingSeasons = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek);

            // Iterate through the matching seasons for the current date
            foreach ($matchingSeasons as $season) {
               
                

                foreach (explode('|', $optionIds) as $index => $optionId) {


                    $optionValue = explode('|', $optionValues)[$index];
                    $optionTenantId = explode('|', $optionTenantIds)[$index];

                    if (!isset($optionValue) || $optionValue == '') {
                        $optionValue = '0';
                        continue;
                    }

                    $optionPrices = $this->getOptionPricesForSeason($optionId, $season->id);
                    $optionType = $this->getOptionType($optionId);

                    if(!isset($pricesMap[$optionTenantId])) {
                        $pricesMap[$optionTenantId]["totalPrice"] = 0;
                        $pricesMap[$optionTenantId]["individualPrices"] = [];
                        $pricesMap[$optionTenantId]["optionValues"] = [];
                    }

                    $pricesMap[$optionTenantId]["optionValues"][$optionId] = $optionValue;

                    if(!isset($pricesMap[$optionTenantId]["individualPrices"][$optionId])) {
                        $pricesMap[$optionTenantId]["individualPrices"][$optionId] = 0;
                    }

                    
                    if (sizeof($optionPrices) > 0) {
                        foreach($optionPrices as $optionPrice) {
                            $multiplierValue = (float)$optionPrice->price;
                            $optionTotalPrice = $this->calculateOptionPrice(
                                $optionType,
                                $optionValue,
                                $optionPrice,
                                $optionPrice->multiplier,
                                $optionPrice->x,
                                $multiplierValue,
                                $currentDate,
                                $dateTo,
                                $timeFrom,
                                $timeTo,
                                $optionId,
                                $people
                            );
                            $pricesMap[$optionTenantId]["totalPrice"] += $optionTotalPrice;
                            
                            $pricesMap[$optionTenantId]["individualPrices"][$optionId] += $optionTotalPrice;
                        }
                    } else {

                    }
                }
            }

            // Move to the next day
            $currentDate->addDay();
        }

        return $pricesMap;
    }

    public function calculateBufferPriceOptions($dateFrom, $dateTo, $optionIds, $optionValues, $optionTenantIds, $people, $bufferTimeBefore, $bufferTimeAfter, $bufferTimeUnit)
    {
        // Convert the date strings to Carbon instances
        $dateFrom = Carbon::createFromFormat('d-m-Y', $dateFrom);
        $dateTo = Carbon::createFromFormat('d-m-Y', $dateTo);

        // Initialize the total price for options
        // $totalPrices = [];

        // Iterate through each day in the date range
        $currentDate = $dateFrom->copy();
        // $individualPrices = [];

        $pricesMap = [];

        while ($currentDate->lte($dateTo)) {
            // Get the day of the week for the current date (e.g., 'Mon')
            $currentDayOfWeek = $currentDate->format('D');

            // Get all seasons for the current date
            $matchingSeasons = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek);

            // Iterate through the matching seasons for the current date
            foreach ($matchingSeasons as $season) {

                foreach (explode('|', $optionIds) as $index => $optionId) {
                    $optionValue = explode('|', $optionValues)[$index];
                    $optionTenantId = explode('|', $optionTenantIds)[$index];

                    if (!isset($optionValue) || $optionValue == '') {
                        $optionValue = '0';
                        continue;
                    }

                    $optionPrices = $this->getOptionBufferPricesForSeason($optionId, $season->id);
                    $optionType = $this->getOptionType($optionId);

                    if(!isset($pricesMap[$optionTenantId])) {
                        $pricesMap[$optionTenantId]["totalPrice"] = 0;
                        $pricesMap[$optionTenantId]["individualPrices"] = [];
                        $pricesMap[$optionTenantId]["optionIds"] = [];
                        $pricesMap[$optionTenantId]["optionValues"] = [];
                    }

                    $pricesMap[$optionTenantId]["optionValues"][$optionId] = $optionValue;

                    if(!isset($pricesMap[$optionTenantId]["individualPrices"][$optionId])) {
                        $pricesMap[$optionTenantId]["individualPrices"][$optionId] = 0;
                    }

                    if (sizeof($optionPrices) > 0) {
                        foreach($optionPrices as $optionPrice) {
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
    
                            $pricesMap[$optionTenantId]["totalPrice"] += $optionTotalPrice;
                            
                            $pricesMap[$optionTenantId]["individualPrices"][$optionId] += $optionTotalPrice;
                        }
                    } else {

                    }
                }
            }

            // Move to the next day
            $currentDate->addDay();
        }

        return $pricesMap;
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

        // Code for parent tenant
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId; // self and child tenant ids.
        
        $filteredEventTypes = EventType::where('name', 'like', '%' . $this->event_name . '%')
                                        ->whereIn('tenant_id', $tenantIds)
                                        ->get();

        $selectedEvent = EventType::find($this->event_type);

        // filtered contacts, venues and venue areas
        $this->filteredContacts = Contact::whereIn('tenant_id', $tenantIds)->get();

        // $tenantIds = [];
        // if($selectedEvent) {
        //     $tenantIds = Tenant::where('parent_id', $selectedEvent->tenant->id)->pluck('id')->toArray();
        //     $tenantIds[] = $selectedEvent->tenant->id;
        // }
        
        $filteredVenues = Venue::whereIn('tenant_id', $tenantIds)->get();

        $filteredAreas = VenueArea::whereIn('tenant_id', $tenantIds)->where('venue_id', $this->selectedVenueId)->get();

        $options = $this->loadOptions();

        $this->dispatchBrowserEvent('date-range-updated', 
        [
            'timeRanges' => $this->time_ranges,
            'selectedEvent' => $selectedEvent,
        ]);

        return view('livewire.quote.add-quote-modal', compact('filteredAreas', 'filteredEventTypes', 'filteredVenues', 'options', 'selectedEvent'));
    }

    public function updatedAreaId()
    {
        $this->selectedOptions = [];
        $this->loadOptions();
    }

    public function updatedSelectedVenueId()
    {
        $this->selectedOptions = [];
        $this->loadOptions();
    }

    public function updatedSeasonId()
    {
        $this->selectedOptions = [];
        $this->loadOptions();
    }


    private function loadOptions()
    {

        // Check if the required fields are set
        if (!$this->date_from || !$this->area_id) {
            $this->options = collect(); // No options to display if date is not set
            return;
        }

        // Convert the date strings to Carbon instances
        $dateFrom = Carbon::createFromFormat('d-m-Y', $this->date_from);
        $currentDate = $dateFrom;
        $currentDayOfWeek = $currentDate->format('D');

        // Find the associated venue ID for the selected area
        $selectedArea = VenueArea::find($this->area_id);
        $selectedVenue = optional($selectedArea)->venue ?? null;
        $selectedEventType = EventType::find($this->event_type);

        $currentTenantId = Session::get('current_tenant_id');
        
        // filtered contacts, venues and venue areas
        $filteredContacts = Contact::where('tenant_id', $currentTenantId)->get();

        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;
        
        // Get the seasons for the selected date and weekday
        $seasonIds = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek)->pluck('id')->toArray();

        // Get the "All" season ID
        $allSeasonIds = Season::where("name", "All")->whereIn('tenant_id', $tenantIds)->get()->pluck('id')->toArray();

        $optionsQuery = Option::orderBy('position')
        ->whereIn('tenant_id', $tenantIds)
        ->where(function ($query) use ($seasonIds, $allSeasonIds) {
            $query->where(function ($q) use ($seasonIds) {
                foreach ($seasonIds as $seasonId) {
                    $q->orWhereRaw("FIND_IN_SET(?, season_ids)", [$seasonId]);
                }
            })->orWhere(function ($q) use ($allSeasonIds) {
                foreach ($allSeasonIds as $allSeasonId) {
                    $q->orWhereRaw("FIND_IN_SET(?, season_ids)", [$allSeasonId]);
                }
            })->orWhereNull('season_ids')->orWhere('season_ids', '');
        });

        
        if ($selectedEventType) {
            $optionsQuery->where(function ($query) use ($selectedEventType) {
                $query->whereRaw('FIND_IN_SET(?, eventtype_ids) > 0', [$selectedEventType->id])
                        ->orWhereNull('eventtype_ids')->orWhere('eventtype_ids', '');
            });
        }

        // Refine additional filters
        /*if ($selectedVenue) {
            $optionsQuery->where(function ($query) use ($selectedVenue) {
                $query->whereRaw('FIND_IN_SET(?, venue_ids) > 0', [$selectedVenue->id])
                        ->orWhereNull('venue_ids');
            });
        }*/

        if ($selectedArea) {
            $optionsQuery->where(function ($query) use ($selectedArea) {
                $query->whereRaw('FIND_IN_SET(?, area_ids) > 0', [$selectedArea->id])
                        ->orWhereNull('area_ids')->orWhere('area_ids', '');
            });
        }

        
        $this->options = $optionsQuery->get();
        // Set values for specific logic options and default values
        foreach ($this->options as $option) {
            if ($option->type === 'logic') {
                $option->value = $this->calculateLogicOptionValues($option->id);
            }
            $this->selectedOptions[$option->id] = $this->selectedOptions[$option->id] ?? $option->value ?? $option->default_value;
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