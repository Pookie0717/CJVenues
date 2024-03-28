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
use App\Models\Staffs;
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
    public $waiters = 0;
    public $venueManagers = 0;
    public $toiletStaffs = 0;
    public $cleaners = 0;
    public $barStaff = 0;
    public $other = 0;
    public $staff_ids;
    public $staff_arr_index = [null, null, null, null, null, null];
    public $timeFrom;
    public $timeTo;

    public $isDrink = true;

    public $isSoftDrink;
    public $isCocktails;

    public $filteredContacts = [];
    public $edit_mode = false;

    public $submitedStaffCount = 0;
    public $submitedTenantIds = [];

    public $staff_individual_ids = [];
    public $staff_individual_prices = [];

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
        $this->validateQuoteData();
        $this->applyStaffs();
        $staff_ids_arr = [
            $this->waiters ? $this->waiters['id'] : $this->waiters, 
            $this->venueManagers ? $this->venueManagers['id'] : $this->venueManagers,
            $this->toiletStaffs ? $this->toiletStaffs['id'] : $this->toiletStaffs,
            $this->cleaners ? $this->cleaners['id'] : $this->cleaners,
            $this->barStaff ? $this->barStaff['id'] : $this->barStaff,
            $this->other ? $this->other['id'] : $this->other,
        ];

        $staff_item_arr = [
            $this->waiters,
            $this->venueManagers,
            $this->toiletStaffs,
            $this->cleaners,
            $this->barStaff,
            $this->other,
        ];

        $selectedTenantArr = [];
        $isTrue = false;
        foreach($staff_item_arr as $staff_ids_arr_item) {
            foreach($selectedTenantArr as $selectedTenant) {
                if($staff_ids_arr_item !== 0 && $staff_ids_arr_item['tenant_id'] == $selectedTenant) {
                    $isTrue = true;
                }
            }
            if($staff_ids_arr_item !== 0 && !$isTrue) {
                $selectedTenantArr[] = $staff_ids_arr_item['tenant_id'];
                $this->submitedStaffCount += 1;
                $isTrue = false;
            }
        }

        $this->eventTypes = [];
        $merged_staff_ids_arr = array_merge($staff_ids_arr, $this->staff_arr_index);
        $merged_staff_ids_arr = array_map(function($value) {
            return $value === null ? 'null' : $value;
        }, $merged_staff_ids_arr);
        $this->staff_ids = implode('|', $merged_staff_ids_arr);

        $timeFromArray = [];
        $timeToArray = [];

        foreach ($this->time_ranges as $time_range) {
            $timeFromArray[] = $time_range['time_from'];
            $timeToArray[] = $time_range['time_to'];
        }

        $timeFrom = implode('|', $timeFromArray);
        $timeTo = implode('|', $timeToArray);
        $this->timeFrom = $timeFrom;
        $this->timeTo = $timeTo;

        $priceBufferVenue = $this->calculateBufferPriceVenue($this->buffer_time_before, $this->buffer_time_after, $this->buffer_time_unit, $this->area_id, $this->staff_ids);

        $priceVenue = $this->calculatePriceVenue($this->date_from, $this->date_to, $timeFrom, $timeTo, $this->area_id);
        // selected area's tenant priceVenue.
        $priceVenue = $priceVenue + $priceBufferVenue;
        $mainPriceOptions = 0;
        $mainTenantId = VenueArea::find($this->area_id)->tenant_id;

        $mainPriceOptionsString = '';
        $mainOptionIds = [];
        $mainOptionValues = [];

        if($this->selectedOptions) {
            // Convert selected options to a comma-separated string format
            $optionIdsIm = implode('|', array_keys($this->selectedOptions));

            // Log::info("-----optionIdsIm". json_encode($optionIdsIm));

            $optionValuesIm = implode('|', array_values($this->selectedOptions));

            $optionIds = explode('|', $optionIdsIm);

            $optionValues = explode('|', $optionValuesIm);

            $cleanedOptionIds = [];
            $cleanedOptionValues = [];
            $cleanedOptionTenantIds = [];

            foreach ($optionIds as $index => $value) {
                $option_arr = Option::where('id', $value)->get();
                if(($option_arr[0]['type'] == 'always') || ($optionValues[$index] !== '' && $optionValues[$index] != 0 && $optionValues[$index] !== 'no')) {
                    $tenantId = Option::find($value)->tenant->id;
                    if (!isset($cleanedOptionIds[$tenantId])) {
                        $cleanedOptionIds[$tenantId] = [];
                        $cleanedOptionValues[$tenantId] = [];
                        $cleanedOptionTenantIds[$tenantId] = [];
                    }
                    if($option_arr[0]['type'] == 'always') {
                        $cleanedOptionIds[$tenantId][] = $value;
                        $cleanedOptionValues[$tenantId][] = 'always';
                        $cleanedOptionTenantIds[$tenantId][] = Option::find($value)->tenant->id;
                    } else {
                        $cleanedOptionIds[$tenantId][] = $value;
                        $cleanedOptionValues[$tenantId][] = $optionValues[$index];
                        $cleanedOptionTenantIds[$tenantId][] = Option::find($value)->tenant->id;
                    }
                }
            }

            foreach($cleanedOptionTenantIds as $tenantId_val => $cleanedOptionTenantId) {
                $optionIds = implode('|', $cleanedOptionIds[$tenantId_val]);
                $optionValues = implode('|', $cleanedOptionValues[$tenantId_val]);
                $optionTenantIds = implode('|', $cleanedOptionTenantId);
                // Calculate regular and buffer prices for options
                $priceBufferOptionsStringArray = $this->calculateBufferPriceOptions($this->date_from, $this->date_to, $optionIds, $optionValues, $optionTenantIds, $this->people, $this->buffer_time_before, $this->buffer_time_after, $this->buffer_time_unit);
                $priceOptionsStringArray = $this->calculatePriceOptions($this->date_from, $this->date_to, $timeFrom, $timeTo, $optionIds, $optionValues, $optionTenantIds, $this->people);

    
                // Loop through the regular option prices
                    $newQuoteNumber = $this->getNewQuoteNumber();
    
                    // Log::info('Option Price for ID ' . json_encode($item['totalPrice']) . ': ' . json_encode($item['individualPrices']));
    
                    // Update the total price
                $priceOptionsStringArray['totalPrice'] = $priceOptionsStringArray['totalPrice'] + $priceBufferOptionsStringArray['totalPrice']; 
                
                foreach($priceOptionsStringArray['individualPrices'] as $optionId => $optionTotalPrice) {
                    if (isset($priceOptionsStringArray['individualPrices'][$optionId]) && isset($priceBufferOptionsStringArray['individualPrices'][$optionId])) {
                        $priceOptionsStringArray['individualPrices'][$optionId] += $priceBufferOptionsStringArray['individualPrices'][$optionId];
                    }
                    if($priceOptionsStringArray['individualPrices'][$optionId]){
    
                    }
                }
    
                // Your existing code to handle the price options string and calculate the final prices
                $priceOptionsString = implode('|', array_values($priceOptionsStringArray['individualPrices']));
    
                $optionIds = [];
                $optionValues = [];
                foreach( $priceOptionsStringArray['individualPrices'] as $optionId => $optionTotalPrice) {
                    $optionIds[] = $optionId;
                    $optionValues[] = $priceOptionsStringArray["optionValues"][$optionId];
                } 

                //caluclate option price
                $priceOptions = array_sum(array_map('floatval', array_values($priceOptionsStringArray['individualPrices'])));
                $calculatedPrice = $priceOptions;
    
                if($mainTenantId == $priceOptionsStringArray['optionTenantId']) {
                    $mainPriceOptions = $calculatedPrice;
                    $mainTenantId = $priceOptionsStringArray['optionTenantId'];
                    $mainPriceOptionsString = $priceOptionsString;
                    $mainOptionIds = $optionIds;
                    $mainOptionValues = $optionValues;
                } else {
                    $staff_ids_arr_option = $staff_ids_arr;
                    foreach($staff_item_arr as $index => $staff_item) {
                        if($staff_item !== 0) {
                            if($staff_item['tenant_id'] == $priceOptionsStringArray['optionTenantId']) {
                                $staff_ids_arr_option[$index] = $staff_item['id'];
                                $this->submitedStaffCount -= 1;
                            } else {
                                $staff_ids_arr_option[$index] = 0;
                            }
                        }
                    }
            
                    $merged_staff_ids_arr = array_merge($staff_ids_arr_option, $this->staff_arr_index);
                    $merged_staff_ids_arr = array_map(function($value) {
                        return $value === null ? 'null' : $value;
                    }, $merged_staff_ids_arr);
                    $this->staff_ids = implode('|', $merged_staff_ids_arr);

                    //calculate staff price
                    $staffPrice_val = 0;
                    $staffPrice_arr = [];
                    $staffIndividualPrices = [];
                    $staffIndividualIds = [];
                    $staffIndividualCount = [];
                    if($this->waiters || $this->venueManagers || $this->toiletStaffs || $this->cleaners) { 
                        $staffPrice_arr = $this->calculateStaffPrice($staff_ids_arr, $this->date_from, $this->date_to, $timeFrom, $timeTo, $this->buffer_time_before, $this->buffer_time_after, $this->buffer_time_unit);
                        $staffPrice_val = $staffPrice_arr['totalPrice'];
                        foreach($staffPrice_arr as $staff_id => $staffPrice_item) {
                            $staffIndividualIds[] = $staff_id;
                            if(is_array($staffPrice_item)) {
                                $staffIndividualCount[] = $staffPrice_item['count'];
                                $staffIndividualPrices[] = $staffPrice_item['price'];
                            }
                        }
                        array_shift($staffIndividualIds);
                    }
                    $calculatedPrice += $staffPrice_val;
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
                        'details' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
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
                        'discount' => 0,
                        'price' => $totalPrice,
                        'price_venue' => 0,
                        'price_options' => $priceOptionsString,
                        'options_ids' => implode("|", $optionIds),
                        'options_values' => implode("|", $optionValues),
                        'buffer_time_before' => $this->buffer_time_before,
                        'buffer_time_after' => $this->buffer_time_after,
                        'buffer_time_unit' => $this->buffer_time_unit,
                        'tenant_id' => $priceOptionsStringArray['optionTenantId'],
                        'staff_ids' => $this->staff_ids,
                        'staff_individual_ids' => implode('|', $staffIndividualIds),
                        'staff_individual_prices' => implode('|', $staffIndividualPrices),
                        'staff_individual_count' => implode('|', $staffIndividualCount),
                    ]);
                    DB::table('system_information')->where('key', 'current_quote_number')->update(['value' => $newQuoteNumber]);
                }
            }
        }

        $newQuoteNumber = $this->getNewQuoteNumber();
        $staff_ids_arr_option = $staff_ids_arr;
        foreach($staff_item_arr as $index => $staff_item) {
            if($staff_item !== 0) {
                if($staff_item['tenant_id'] == $mainTenantId) {
                    $staff_ids_arr_option[$index] = $staff_item['id'];
                    $this->submitedStaffCount -= 1;
                } else {
                    $staff_ids_arr_option[$index] = 0;
                }
            }
        }

        $merged_staff_ids_arr = array_merge($staff_ids_arr_option, $this->staff_arr_index);
        $merged_staff_ids_arr = array_map(function($value) {
            return $value === null ? 'null' : $value;
        }, $merged_staff_ids_arr);
        $this->staff_ids = implode('|', $merged_staff_ids_arr);

        $this->submitedTenantIds[] = $mainTenantId;

        //calculate staff price
        $staffPrice_val = 0;
        $staffIndividualPrices = [];
        $staffIndividualIds = [];
        $staffIndividualCount = [];
        if($this->waiters || $this->venueManagers || $this->toiletStaffs || $this->cleaners) { 
            $this->staff_individual_prices = [];
            $this->staff_individual_ids = [];
            $staffPrice_arr = $this->calculateStaffPrice($staff_ids_arr, $this->date_from, $this->date_to, $timeFrom, $timeTo,  $this->buffer_time_before, $this->buffer_time_after, $this->buffer_time_unit);
            $staffPrice_val = $staffPrice_arr['totalPrice'];
            foreach($staffPrice_arr as $staff_id => $staffPrice_item) {
                $staffIndividualIds[] = $staff_id;
                if(is_array($staffPrice_item)) {
                    foreach($staffPrice_item as $staffPriceItem) {
                        $staffIndividualPrices[] = $staffPrice_item['price'];
                        $staffIndividualCount[] = $staffPrice_item['count'];
                    }
                }
            }
        }
        Log::info($staffIndividualPrices);

        array_shift($staffIndividualIds);
        $calculatedPrice = $priceVenue + $mainPriceOptions + $staffPrice_val;
        $totalPrice = $this->applyDiscount($calculatedPrice, $this->discount);

        Quote::create([
            'contact_id' => $this->contact_id,
            'status' => 'Draft',
            'details' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
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
            'tenant_id' => $mainTenantId,
            'staff_ids' => $this->staff_ids,
            'staff_individual_ids' => implode('|', $staffIndividualIds),
            'staff_individual_prices' => implode('|', $staffIndividualPrices),
            'staff_individual_count' => implode('|', $staffIndividualCount),
        ]);

        DB::table('system_information')->where('key', 'current_quote_number')->update(['value' => $newQuoteNumber]);
        $isTenant = false;
        while($this->submitedStaffCount > 0) {
            $newQuoteNumber = $this->getNewQuoteNumber();
            $staff_ids_arr_staff = $staff_ids_arr;
            $currentStaffTenant = null;
            foreach($staff_item_arr as $index => $staff_item) {
                foreach($this->submitedTenantIds as $submitedTenantId) {
                    if($staff_item !== 0 && $submitedTenantId == $staff_item['tenant_id']) {
                        $isTenant = true;
                    }
                }
                if(!$isTenant && $staff_item !== 0) {
                    $staff_ids_arr_staff[$index] = $staff_item ? $staff_item['id'] : 0;
                    $isTenant = false;
                    $this->submitedTenantIds[] = $staff_item ? $staff_item['tenant_id'] : 0;
                    $currentStaffTenant = $staff_item ? $staff_item['tenant_id'] : null;
                } elseif($staff_item !== 0) {
                    $staff_ids_arr_staff[$index] = $staff_item['id'];
                }
            }
            $merged_staff_ids_arr = array_merge($staff_ids_arr_staff, $this->staff_arr_index);
            $merged_staff_ids_arr = array_map(function($value) {
                return $value === null ? 'null' : $value;
            }, $merged_staff_ids_arr);
            $this->staff_ids = implode('|', $merged_staff_ids_arr);

            //calculate staff price
            $staffPrice_val = 0;
            $staffPrice_arr = $this->calculateStaffPrice($staff_ids_arr, $this->date_from, $this->date_to, $timeFrom, $timeTo,  $this->buffer_time_before, $this->buffer_time_after, $this->buffer_time_unit);
            $staffIndividualPrices = [];
            $staffIndividualIds = [];
            $staffIndividualCount = [];
            $staffPrice_val = $staffPrice_arr['totalPrice'];
            foreach($staffPrice_arr as $staff_id => $staffPrice_item) {
                $staffIndividualIds[] = $staff_id;
                if(is_array($staffPrice_item)) {
                    $staffIndividualPrices[] = $staffPrice_item['price'];
                    $staffIndividualCount[] = $staffPrice_item['count'];
                }
            }
            array_shift($staffIndividualIds);

            $calculatedPrice = $staffPrice_val;
            $totalPrice = $this->applyDiscount($calculatedPrice, $this->discount);
            Quote::create([
                'contact_id' => $this->contact_id,
                'status' => 'Draft',
                'details' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
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
                'buffer_time_before' => $this->buffer_time_before,
                'buffer_time_after' => $this->buffer_time_after,
                'buffer_time_unit' => $this->buffer_time_unit,
                'tenant_id' => $currentStaffTenant,
                'staff_ids' => $this->staff_ids,
                'staff_individual_ids' => implode('|', $staffIndividualIds),
                'staff_individual_prices' => implode('|', $staffIndividualPrices),
                'staff_individual_count' => implode('|', $staffIndividualCount),
            ]);
            $this->submitedStaffCount -= 1;
            DB::table('system_information')->where('key', 'current_quote_number')->update(['value' => $newQuoteNumber]);
        }

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
            'time_ranges',
            'staff_ids'
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

    private function getOptionPricesForSeason($optionId, $seasonId)
    {
        $options = Option::find($optionId)->prices()
            ->where('type', 'option')
            ->where('season_ids', $seasonId)
            ->get();
        $option_arr = [];
        foreach($options as $option) {
            $option_area_arr = explode(',', $option['option_area_ids']);
            foreach($option_area_arr as $option_area_item) {
                if($this->area_id == $option_area_item) {
                    $option_arr[] = $option;
                }
            }
        }
        return $option_arr;        
    }

    private function getOptionBufferPricesForSeason($optionId, $seasonId)
    {
        $options = Option::find($optionId)->prices()
            ->where('type', 'option')
            ->where('season_ids', $seasonId)
            ->get();
        $option_arr = [];
        foreach($options as $option) {
            $option_area_arr = explode(',', $option['option_area_ids']);
            $option_extra_tier_type = explode(',', $option['extra_tier_type']);
            foreach($option_area_arr as $option_area_item) {
                if($this->area_id == $option_area_item) {
                    foreach($option_extra_tier_type as $option_extra_tier_type_item) {
                        if($option_extra_tier_type_item == 'buffer_before' || $option_extra_tier_type_item == 'buffer_after') {
                            $option_arr[] = $option;
                        }
                    }
                }
            }
        }
        return $option_arr;
    }

    // Helper method to calculate the number of days between date_from and date_to
    private function calculateNumberOfDays($dateFrom, $dateTo)
    {
        $startDate = $dateFrom;
        $endDate = $dateTo;

        $diffInDays = $startDate->diffInDays($endDate);
        $diffInDays = $diffInDays + 1;
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
            $hoursDifference = (($toHours - $fromHours) + ($toMinutes - $fromMinutes) / 60);

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

    private function staffPrice($staff_ids) {
        $staff_arr = explode('|', $staff_ids);
        foreach($staff_arr as $staff_arr_val) {
            $staff_price = Price::where('staff_id', $staff_arr_val)->get();
            if($staff_price->count() > 0) {
                if($bufferUnit == 'days') {
                    if($staff_price[0]['multiplier'] == 'hourly') {
                        $totalBufferPrice += (($bufferBefore + $bufferAfter) * 8) * $staff_price[0]['price'];
                    } else {
                        $totalBufferPrice += ($bufferBefore + $bufferAfter) * $staff_price[0]['price'];
                    }
                } else {
                    if($staff_price[0]['multiplier'] == 'daily') {
                        $totalBufferPrice += ($staff_price[0]['price'] / 8) * ($bufferBefore + $bufferAfter);
                    } else {
                        $totalBufferPrice += $staff_price[0]['price'] * ($bufferBefore + $bufferAfter);
                    }
                }
            }
        }
        
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
        ->where('season_ids', $seasonId)
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
            } else if ($price->multiplier == 'hourly') {
                // If both buffer and price are in hours, calculate directly
                return (intval($bufferBefore) + intval($bufferAfter)) * $price->price;
            }
        } else if ($bufferUnit == 'days') {
            if ($price->multiplier == 'daily') {
                // If both buffer and price are in days, calculate directly
                return (intval($bufferBefore) + intval($bufferAfter)) * $price->price;
            } else if ($price->multiplier == 'hourly') {
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

        // while ($currentDate->lte($dateToC)) {
            // Get the day of the week for the current date (e.g., 'Mon')
            $currentDayOfWeek = $currentDate->format('D');

            // Get all seasons for the current date
            $matchingSeasons = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek);

            // Iterate through the matching seasons for the current date
            foreach ($matchingSeasons as $season) {

                 // Check if there is a price associated with the area for this season
                $areaPrice = $area->prices()
                    ->where('type', 'area')
                    ->where('season_ids', $season->id)
                    ->where('extra_tier_type', 'like', '%event%')
                    ->first();

                // Check if there is a price associated with the venue for this season
                $venuePrice = $venue->prices()
                    ->where('type', 'venue')
                    ->where('season_ids', $season->id)
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
                        $totalPrice += $multiplierValue * $days;
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
        // }
        return $totalPrice;
    }

    private function calculateStaffPrice($staff_ids, $dateFrom, $dateTo, $timeFrom, $timeTo, $bufferTimeBefore, $bufferTimeAfter, $bufferTimeUnit)
    {
        $dateFromC = Carbon::createFromFormat('d-m-Y', $this->date_from);
        $dateToC = Carbon::createFromFormat('d-m-Y', $this->date_to);
        $currentDate = $dateFromC->copy();

        $staff_count = [];
        $staff_prices = [];
        
        $priceResult = [];
        $priceResult['totalPrice'] = 0;
        $totalPrice = 0;
        
        $days = $this->calculateNumberOfDays($dateFromC, $dateToC);
        $days = $days == 0 ? $days = 1 : $days;
        $hours = $this->calculateNumberOfHours($dateFrom, $this->timeFrom, $dateTo, $this->timeTo);
        $people = $this->people;

        $staff_arr = $staff_ids;
        $totalPrice = 0;
        $staff_price_buffer_items = [];
        $staff_price_items = [];
        $extra_tier_types = [];
        $staffCount = [];
        foreach($staff_arr as $staff_arr_val) {
            if($staff_arr_val !== 0) {
                $staff_count = Staffs::where('id', $staff_arr_val)->get();
                $duration_arr = explode(',', $staff_count[0]['duration_type']);
                $from_arr = explode(',', $staff_count[0]['from']);
                $to_arr = explode(',', $staff_count[0]['to']);
                $count_arr = explode(',', $staff_count[0]['count']);
                foreach($duration_arr as $index => $duration) {
                    if (($duration == 'people' && $from_arr[$index] <= $this->people && $this->people <= $to_arr[$index]) ||
                        ($duration == 'hour' && $from_arr[$index] <= $hours && $hours <= $to_arr[$index]) ||
                        ($duration == 'days' && $from_arr[$index] <= $days && $days <= $to_arr[$index])) {
                        $staff_prices = Price::where('staff_id', $staff_arr_val)->get();
                        foreach($staff_prices as $staff_price) {
                            if(isset($staff_price)){
                                $extra_tier_types = explode(',', $staff_price['extra_tier_type']);
                                foreach ($extra_tier_types as $extra_tier_type) {
                                    if (($extra_tier_type == 'buffer_before' || $extra_tier_type == 'buffer_after') && !in_array($staff_price, $staff_price_buffer_items)) {
                                        $staff_price['count'] = $count_arr[$index];
                                        $staff_price_buffer_items[] = $staff_price;
                                    } elseif ($extra_tier_type !== 'buffer_before' && $extra_tier_type !== 'buffer_after' && !in_array($staff_price, $staff_price_items)) {
                                        $staff_price['count'] = $count_arr[$index];
                                        $staff_price_items[] = $staff_price;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        foreach($staff_price_items as $index => $staff_price_item) {
            $price = 0;
            $x = $staff_price_item['x'];
            $multiplierType = $staff_price_item['multiplier'];
            $quantity = $staff_price_item['price'];

            switch ($multiplierType) {    
                case 'daily':
                case 'daily_pp':
                    $price = $staff_price_item['price'] * $days;
                    break;
                case 'hourly':
                case 'hourly_pp':
                    $price = $staff_price_item['price'] * $hours;
                    break;
                case 'event':
                case 'event_pp':
                    $price = $staff_price_item['price'];
                    break;
                case 'every_x_p':
                    $price = $staff_price_item['price'];
                    break;
                case 'every_x_d':
                    $price = $staff_price_item['price'] * ceil($days / $x);
                    break;
                case 'every_x_h':
                    $price = $staff_price_item['price'] * $hours / $x;
                    break;
            }
            if (str_ends_with($multiplierType, '_pp')) {
                $price = $price + ($people * $days);
                if($multiplierType == 'daily_pp') {
                    $price -= $days;
                }
            }
    
            if (str_ends_with($multiplierType, '_x_p')) {
                $price *= ($people / $x);
            }
            if(!isset($priceResult[$staff_price_item['staff_id']]['price'])) {
                $priceResult[$staff_price_item['staff_id']]['price'] = 0;
                $priceResult[$staff_price_item['staff_id']]['count'] = 0;
            }
            $price = $price * $staff_price_item['count'];
            $totalPrice += $price;
            $priceResult[$staff_price_item['staff_id']]['count'] = $staff_price_item['count'];
            $priceResult[$staff_price_item['staff_id']]['price'] += $price;
            $priceResult['totalPrice'] = $totalPrice;
        }



        $totalBufferHours = 0;
        if ($bufferTimeUnit == 'days') {
            $totalBufferHours = ($bufferTimeBefore + $bufferTimeAfter) * 8;
        } else { // Assuming the unit is hours
            $totalBufferHours = $bufferTimeBefore + $bufferTimeAfter;
        }
        // Calculate days and hours from the total buffer hours
        $days = ceil($totalBufferHours / 8);
        $hours = $totalBufferHours;
        foreach($staff_price_buffer_items as $index => $staff_price_buffer_item) {
            $price = 0;
            $x = $staff_price_buffer_item['x'];
            $multiplierType = $staff_price_buffer_item['multiplier'];
            $quantity = $staff_price_buffer_item['price'];
            switch ($multiplierType) {    
                case 'daily':
                case 'daily_pp':
                    $price = $staff_price_buffer_item['price'] * $days;
                    break;
                case 'hourly':
                case 'hourly_pp':
                    $price = $staff_price_buffer_item['price'] * $hours;
                    break;
                case 'event':
                case 'event_pp':
                    $price = $staff_price_buffer_item['price'];
                    break;
            }
            if (str_ends_with($multiplierType, '_pp')) {
                $price = $price + ($people * $days);
                if($multiplierType == 'daily_pp') {
                    $price -= $days;
                }
            }
            if(!isset($priceResult[$staff_price_buffer_item['staff_id']]['price'])) {
                $priceResult[$staff_price_buffer_item['staff_id']]['price'] = 0;
                $priceResult[$staff_price_buffer_item['staff_id']]['count'] = 0;
            }
            $price = $price * $staff_price_buffer_item['count'];
            $totalPrice += $price;
            $priceResult[$staff_price_buffer_item['staff_id']]['count'] = $staff_price_buffer_item['count'];
            $priceResult[$staff_price_buffer_item['staff_id']]['price'] += $price;
            $priceResult['totalPrice'] = $totalPrice;
        }
        return $priceResult;
    }

    private function calculateOptionPrice($optionType, $optionValue, $optionPrice, $multiplierType, $x, $multiplierValue, $dateFrom, $dateTo, $timeFrom, $timeTo, $optionId, $people)
    {
        $price = 0;

        $days = $this->calculateNumberOfDays($dateFrom, $dateTo);
        $days = $days == 0 ? $days = 1 : $days;
        $hours = $this->calculateNumberOfHours($dateFrom, $this->timeFrom, $dateTo, $this->timeTo);
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
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, ceil($days / $x), $optionId, $people, $hours, $days);
                break;
            case 'every_x_h':
                $price = $this->calculatePriceBasedOnType($optionType, $optionValue, $optionPrice, $multiplierValue, $hours / $x, $optionId, $people, $hours, $days);
                break;
        }

        if (str_ends_with($multiplierType, '_pp')) {
            $price = $price * $people;
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

        // Log::info('Buffer Hours ' . $hours);


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
            $price += $people * $days;
        }
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
        if ($optionType == 'yes_no') {
            $price = $optionValue == 'yes' ? $multiplierValue * $quantity : 0;
        } elseif ($optionType == 'always') {
            $price = $multiplierValue * $quantity * $this->getDefaultOptionValue($optionId);
        } elseif ($optionType == 'number') {
            $price = $multiplierValue * (float)$optionValue * $quantity;
        } elseif ($optionType == 'radio' || $optionType == 'checkbox') {
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
        } elseif ($optionType == 'logic') {

            $optionValues = explode('|', $this->getOptionValues($optionId));

            $logicOption = $this->getLogicOptionDetails($optionId, $people, $hours, $days);

            $logicOptionValue = $logicOption ? $optionValues[0] : $optionValues[1];

            $price = $multiplierValue * (float)$logicOptionValue * $quantity;
        
        } else {
            $price = $multiplierValue * $quantity;
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
                if (count($parts) == 3) {
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
        $pricesMap["totalPrice"] = 0;
        $pricesMap["individualPrices"] = [];
        $pricesMap["optionValues"] = [];

        // while ($currentDate->lte($dateTo)) {
            // Get the day of the week for the current date (e.g., 'Mon')
            $currentDayOfWeek = $currentDate->format('D');

            // Get all seasons for the current date
            $matchingSeasons = $this->getSeasonsForDateAndWeekday($currentDate, $currentDayOfWeek); 
            // Iterate through the matching seasons for the current date
            foreach ($matchingSeasons as $season) {
                foreach (explode('|', $optionIds) as $index => $optionId) {
                    $optionValue = explode('|', $optionValues)[$index];
                    $optionTenantId = explode('|', $optionTenantIds)[$index];
                    $pricesMap['optionTenantId'] = $optionTenantId;

                    if (!isset($optionValue) || $optionValue == '') {
                        $optionValue = '0';
                        continue;
                    }

                    $optionPrices = $this->getOptionPricesForSeason($optionId, $season->id);
                    $optionType = $this->getOptionType($optionId);

                    $pricesMap["optionValues"][$optionId] = $optionValue;

                    if(!isset($pricesMap["individualPrices"][$optionId])) {
                        $pricesMap["individualPrices"][$optionId] = 0;
                    }
                    
                    if (sizeof($optionPrices) > 0) {
                        foreach($optionPrices as $optionPrice) {
                            $multiplierValue = $optionPrice->price;
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
                            $pricesMap["totalPrice"] += $optionTotalPrice;
                            $pricesMap["individualPrices"][$optionId] += $optionTotalPrice;
                        }
                    }
                }
            }

            // Move to the next day
            $currentDate->addDay();
        // }
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
        $individualPrices = [];

        $pricesMap = [];
        $pricesMap["totalPrice"] = 0;
        $pricesMap["individualPrices"] = [];
        $pricesMap["optionIds"] = [];
        $pricesMap["optionValues"] = [];

        // while ($currentDate->lte($dateTo)) {
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


                    $pricesMap["optionValues"][$optionId] = $optionValue;

                    if(!isset($pricesMap["individualPrices"][$optionId])) {
                        $pricesMap["individualPrices"][$optionId] = 0;
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
    
                            // Log::info('optionPrice ' . $optionId . ': ' . $optionPrice);
    
                            // Log::info('Single Buffer Price for Option ' . $optionId . ': ' . $optionTotalPrice);
    
                            $pricesMap["totalPrice"] += $optionTotalPrice;
                            
                            $pricesMap["individualPrices"][$optionId] += $optionTotalPrice;
                        }
                    }
                }
            }
            // Move to the next day
            $currentDate->addDay();
        // }
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
        $isDrink = $this->isDrink;

        return view('livewire.quote.add-quote-modal', compact('filteredAreas', 'filteredEventTypes', 'filteredVenues', 'options', 'selectedEvent', 'isDrink'));
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
        if ($selectedVenue) {
            $optionsQuery->where(function ($query) use ($selectedVenue) {
                $query->whereRaw('FIND_IN_SET(?, venue_ids) > 0', [$selectedVenue->id])
                ->orWhereNull('venue_ids');
            });
        }
        if ($selectedArea) {
            $optionsQuery->where(function ($query) use ($selectedArea) {
                $query->whereRaw('FIND_IN_SET(?, area_ids) > 0', [$selectedArea->id])
                ->orWhereNull('area_ids')->orWhere('area_ids', '');
            });
        }
        
        $this->options = $optionsQuery->get();
        // Set values for specific logic options and default values
        foreach ($this->options as $option) {
            if ($option->type == 'logic') {
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
        } else if (is_numeric($discount)) {
            // If the discount is a numeric value, treat it as a flat amount
            $discountAmount = $discount;
        } else {
            // Handle invalid discount format
            throw new \Exception("Invalid discount format");
        }

        // Subtract the discount amount from the calculated price
        return max($calculatedPrice - $discountAmount, 0); // Ensure the total doesn't go below 0
    }

    public function updateDrinkState($value) 
    {
        if($value == 'softDrink') {
            $this->isSoftDrink = !$this->isSoftDrink;
        } else if($value == 'cocktails') {
            $this->isCocktails = !$this->isCocktails;
        } else {
            $this->isDrink = !$this->isDrink;
            $this->isSoftDrink = false;
            $this->isCocktails = false;
        }
    }

    private function applyStaffs()
    {
        // Get count of staffs
        $areaId = $this->area_id;
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId; // self and child tenant ids.

        $get_waiters = Staffs::where('type', 'waiters')->get()->filter(function ($staff) use ($tenantIds) {
            return in_array($staff->tenant_id, $tenantIds);
        });
        $get_cleaners = Staffs::where('type', 'cleaners')->get()->filter(function ($staff) use ($tenantIds) {
            return in_array($staff->tenant_id, $tenantIds);
        });
        $get_toiletStaffs = Staffs::where('type', 'toilet staff')->get()->filter(function ($staff) use ($tenantIds) {
            return in_array($staff->tenant_id, $tenantIds);
        });
        $get_venueManagers = Staffs::where('type', 'venue manager')->get()->filter(function ($staff) use ($tenantIds) {
            return in_array($staff->tenant_id, $tenantIds);
        });
        $get_barStaff = Staffs::where('type', 'bar staff')->get()->filter(function ($staff) use ($tenantIds) {
            return in_array($staff->tenant_id, $tenantIds);
        });
        $get_other = Staffs::where('type', 'other')->get()->filter(function ($staff) use ($tenantIds) {
            return in_array($staff->tenant_id, $tenantIds);
        });

        // $get_waiters = Staffs::where('type', 'waiters')->get()->filter(function ($staff) use ($tenantIds) {
        //     $tenantId_col = $staff->tenant_id;
        //     foreach($tenantIds as $tenantId_val_item) {
        //         return $tenantId_val == $tenantId_col;
        //     }
        // });
        // $get_cleaners = Staffs::where('type', 'cleaners')->get()->filter(function ($staff) use ($tenantIds) {
        //     $tenantId_col = $staff->tenant_id;
        //     foreach($tenantIds as $tenantId_val_item) {
        //         return $tenantId_val == $tenantId_col;
        //     }
        // });
        // $get_toiletStaffs = Staffs::where('type', 'toilet staff')->get()->filter(function ($staff) use ($tenantIds) {
        //     $tenantId_col = $staff->tenant_id;
        //     foreach($tenantIds as $tenantId_val_item) {
        //         return $tenantId_val == $tenantId_col;
        //     }
        // });
        // $get_venueManagers = Staffs::where('type', 'venue manager')->get()->filter(function ($staff) use ($tenantIds) {
        //     $tenantId_col = $staff->tenant_id;
        //     foreach($tenantIds as $tenantId_val_item) {
        //         return $tenantId_val == $tenantId_col;
        //     }
        // });
        $selected_date_from = explode('-', $this->date_from);
        $selected_date_to = explode('-', $this->date_to);
        $selected_date_between = Carbon::parse($this->date_to)->diffInDays(Carbon::parse($this->date_from));
        $selected_date_between = $selected_date_between == 0 ? 1 : $selected_date_between;
        $calculatedPrice = 0;
        
        // Initialize the waiters, cleaners, toilet staffs, venue managers, soft drinks, and cocktails variables
        $staffTypes = [
            'waiters' => $get_waiters,
            'venueManagers' => $get_venueManagers,
            'toiletStaffs' => $get_toiletStaffs,
            'cleaners' => $get_cleaners,
            'bar staff' => $get_barStaff,
            'other' => $get_other,
        ];
        
        foreach ($staffTypes as $key => $staffType) {
            if ($staffType->isNotEmpty()) {
                foreach ($staffType as $staff) {
                    $staff_option_values = explode(',', $staff['option_values']);
                    foreach($staff_option_values as $staff_option_value) {
                        if(!$staff_option_value) {
                            $staff_from_arr = explode(',', $staff['from']);
                            $staff_to_arr = explode(',', $staff['to']);
                            $staff_count_arr = explode(',', $staff['count']);
                            $staff_duration_arr = explode(',', $staff['duration_type']);
                            $count_staff_duration_arr = count($staff_duration_arr) > 1 ? count($staff_duration_arr) : count($staff_duration_arr);
                            for ($i = 0; $i < $count_staff_duration_arr; $i++) {
                                switch ($staff_duration_arr[$i]) {
                                    case 'day':
                                        if ($staff_from_arr[$i] <= $selected_date_between && $staff_to_arr[$i] >= $selected_date_between) {
                                            if (!$this->{$key}) {
                                                $this->{$key} = $staff;
                                                $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                            } elseif ($this->{$key}['count'] < $staff_count_arr[$i]) {
                                                $this->{$key} = $staff;
                                                $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                            }
                                        }
                                        break;
                                    case 'people':
                                        if ($staff_from_arr[$i] < $this->people && $staff_to_arr[$i] > $this->people) {
                                            if (!$this->{$key}) {
                                                $this->{$key} = $staff;
                                                $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                            } elseif ($this->{$key}['count'] < $staff_count_arr[$i]) {
                                                $this->{$key} = $staff;
                                                $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                            }
                                        }
                                        break;
                                    case 'hour':
                                        if (!$this->{$key}) {
                                            $this->{$key} = $staff;
                                            $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                        } elseif ($this->{$key}['count'] < $staff_count_arr[$i]) {
                                            $this->{$key} = $staff;
                                            $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                        }
                                        break;
                                }
                            }
                        } else {
                            foreach($this->selectedOptions as $selectedOption) {
                                if($staff_option_value == $selectedOption){
                                    $staff_from_arr = explode(',', $staff['from']);
                                    $staff_to_arr = explode(',', $staff['to']);
                                    $staff_count_arr = explode(',', $staff['count']);
                                    $staff_duration_arr = explode(',', $staff['duration_type']);
                                    $count_staff_duration_arr = count($staff_duration_arr) > 1 ? count($staff_duration_arr) : count($staff_duration_arr);
                                    for ($i = 0; $i < $count_staff_duration_arr; $i++) {
                                        switch ($staff_duration_arr[$i]) {
                                            case 'day':
                                                if ($staff_from_arr[$i] <= $selected_date_between && $staff_to_arr[$i] >= $selected_date_between) {
                                                    if (!$this->{$key}) {
                                                        $this->{$key} = $staff;
                                                        $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                                    } elseif ($this->{$key}['count'] < $staff_count_arr[$i]) {
                                                        $this->{$key} = $staff;
                                                        $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                                    }
                                                }
                                                break;
                                            case 'people':
                                                if ($staff_from_arr[$i] < $this->people && $staff_to_arr[$i] > $this->people) {
                                                    if (!$this->{$key}) {
                                                        $this->{$key} = $staff;
                                                        $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                                    } elseif ($this->{$key}['count'] < $staff_count_arr[$i]) {
                                                        $this->{$key} = $staff;
                                                        $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                                    }
                                                }
                                                break;
                                            case 'hour':
                                                if (!$this->{$key}) {
                                                    $this->{$key} = $staff;
                                                    $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                                } elseif ($this->{$key}['count'] < $staff_count_arr[$i]) {
                                                    $this->{$key} = $staff;
                                                    $this->staff_arr_index[array_search($key, array_keys($staffTypes))] = $i;
                                                }
                                                break;
                                        }
                                    }
                                } 
                            }
                        }
                    }
                }
            }
        }
    }
}