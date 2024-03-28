<?php

namespace App\Http\Controllers;

use App\DataTables\QuotesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\Price;
use App\Models\Season;
use App\Models\Staffs;
use App\Models\Option;
use App\Models\Tenant;
use App\Models\BlockedArea;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;

class QuotesController extends Controller
{

    //public variables for edit-mode


    public function index(QuotesDataTable $dataTable)
    {
        return $dataTable->render('pages.quotes.quotes');
    }

    public function update(Request $request, $id) {
        $updatedQuote = $request;
        Log::info($updatedQuote );
        $options_name = [];
        $options_id = [];
        $venues_name = [];
        $staffs_name = [];
        $options_prices = [];
        $options_count = [];
        $staffs_prices = [];
        $staffs_count = [];
        $staff_ids = [];
        $venue_prices = 0;
        $totalPrice = 0;
        $quote = Quote::find($id);
        $staff_ids_arr = explode('|', $quote->staff_ids);
        $details = $updatedQuote->details;
        $people = $updatedQuote->people;
        $extra_name = [];
        $extra_prices = [];
        $extra_count = [];
        $venue_count = 0;
        if(count($updatedQuote->options) > 0) {
            foreach($updatedQuote->options as $index => $option) {
                $options_name[] = $option[0];
                $options_id[] = $option['id'];
                $options_count[] = $option['1'];
                $options_prices[] = $option['2'] * $option['1'];
                $totalPrice += (int)$option['2'] * $option['1'];
            }
        }
        if(count($updatedQuote->venues) > 0) {
            foreach($updatedQuote->venues as $index => $venue) {
                $venues_name[] = $venue[0];
                $venue_prices = $venue['2'];
                $totalPrice += (int)$venue['2'];
                $venue_count = $venue['1'];
            }
        }
        if(count($updatedQuote->staffs) > 0) {
            foreach($updatedQuote->staffs as $index => $staff) {
                $staffs_name[] = $staff[0];
                $staffs_prices[] = $staff['2'] * $staff['1'];
                $staffs_count[] = $staff['1'];
                $staff_ids[] = $staff['id'];
                $totalPrice += (int)$staff['2'] * $staff['1'];
            }
            $flag = false;
            for ($i = 0; $i < 6; $i++) { 
                foreach ($staff_ids as $staff_id) {
                    if ($staff_ids_arr[$i] == $staff_id) {
                        $flag = true;
                        break;
                    }
                }
                if(!$flag) {
                    $staff_ids_arr[$i] = 0;
                }
                $flag = false;
            }
            // Check if the current staff ID matches any of the IDs in the range 0 to 5     
        }
        if(count($updatedQuote->extra) > 0) {
            foreach($updatedQuote->extra as $index => $extra) {
                if($extra && $extra[0] && $extra['1'] && $extra['2']) {
                    $extra_name[] = $extra[0];
                    $extra_count[] = $extra['1'];
                    $extra_prices[] = $extra['2'] * $extra['1'];
                    $totalPrice += (int)$extra['2'] * $extra['1'];
                }
            }
        }
        Log::info($extra_prices);
        if ($quote) {
            $quote->version = $quote->version + 1;
            $quote->status = 'Draft';
            $quote->details = $details;
            $quote->options_name = $options_name ? implode('|', $options_name) : null;
            $quote->venues_name = $venues_name ? implode('|', $venues_name) : null;
            $quote->staffs_name = $staffs_name ? implode('|', $staffs_name) : null;
            $quote->options_ids = $options_id ? implode('|', $options_id) : null;
            $quote->options_count = $options_count ? implode('|', $options_count) : null;
            $quote->price_options = $options_prices ? implode('|', $options_prices) : null;
            $quote->staff_individual_prices = $staffs_prices ? implode('|', $staffs_prices) : null;
            $quote->staff_individual_count = $staffs_count ? implode('|', $staffs_count) : null;
            $quote->staff_ids = $staff_ids_arr ? implode('|', $staff_ids_arr) : null;
            $quote->staff_individual_ids = $staff_ids ? implode('|', $staff_ids) : null;
            $quote->extra_items_name = $extra_name ? implode('|', $extra_name) : null;
            $quote->extra_items_price = $extra_name ? implode('|', $extra_prices) : null;
            $quote->extra_items_count = $extra_name ? implode('|', $extra_count) : null;
            $quote->price_venue = $venue_prices;
            $quote->people = $people;
            $quote->price = $totalPrice;
            $quote->calculated_price = $totalPrice;
            $quote->venue_count = $venue_count;
            $quote->save();
        }
        return response()->json(['message' => 'Quote submitted successfully!']);
    }

    public function book(Request $request, $id)
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return response()->json(['error' => 'Quote not found'], 404);
        }

        DB::beginTransaction();

        try {
            $quote->status = 'booked';
            $quote->save();

            // Format the dates correctly
            $startDate = new \DateTime($quote->date_from);
            $endDate = new \DateTime($quote->date_to);

            // Convert dates to the format that the database expects
            $formattedStartDate = $startDate->format('Y-m-d');
            $formattedEndDate = $endDate->format('Y-m-d');

            $exists = BlockedArea::where('area_id', $quote->area_id)
                                 ->whereDate('start_date', $formattedStartDate)
                                 ->whereDate('end_date', $formattedEndDate)
                                 ->exists();

            if (!$exists) {
                $blockedArea = new BlockedArea();
                $blockedArea->area_id = $quote->area_id;
                $blockedArea->start_date = $formattedStartDate;
                $blockedArea->end_date = $formattedEndDate;
                $blockedArea->save();

                DB::commit();
                return response()->json(['message' => 'Quote has been booked and area blocked'], 200);
            } else {
                // If area is already blocked, we should roll back any changes.
                DB::rollback();
                return response()->json(['error' => 'The area is already blocked'], 409); // 409 Conflict
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Booking failed: ' . $e->getMessage()], 500);
        }
    }

    public function show(Quote $quote, Contact $contact, Season $season, Option $option, Staffs $staffs)
    {
        // Get the quote_number from the current $quote object
        $quote_number = $quote->quote_number;
        $contact_id = $quote->contact_id;

        // Get the date_from and date_to values from the $quote model
        $dateFrom = $quote->date_from;
        $dateTo = $quote->date_to;
        $details = $quote->details;

        $discount = $quote->discount;

        // Fetch related quotes based on the determined quote_number
        $relatedQuotes = Quote::where('quote_number', $quote_number)
            ->orderBy('version')
            ->get();

        $associatedContact = Contact::where('id', $contact_id)
            ->get();

        $extraItemsName = explode('|', $quote->extra_items_name);
        $extraItemsCount = explode('|', $quote->extra_items_count);
        $extraItemsPrice = explode('|', $quote->extra_items_price);
        $staff_individual_ids = explode('|', $quote->staff_individual_ids);

        $staffIds = explode('|', $quote->staff_ids);
        if(count($staffIds) <= 1) {
            $staffIds = [0, 0, 0, 0, 0, 0];
        }
        $useFlag = false;
        foreach($staffIds as $index => $staffId) {
            foreach($staff_individual_ids as $staff_individual_id) {
                if($staff_individual_id == $staffId){
                    $useFlag = true;
                }
            }
            if($useFlag == false) {
                $staffIds[$index] = 0;
            }
            $useFlag = false;
        }
        $waiter = Staffs::where('id', $staffIds[0])->get();
        $venueManagers = Staffs::where('id', $staffIds[1])->get();
        $toiletStaffs = Staffs::where('id', $staffIds[2])->get();
        $cleaners = Staffs::where('id', $staffIds[3])->get();
        $barStaff = Staffs::where('id', $staffIds[4])->get();
        $other = Staffs::where('id', $staffIds[5])->get();

        if(count($waiter)>0) {
            $waiter[0]['quantity'] = 1;
        }
        if(count($venueManagers)>0){
            $venueManagers[0]['quantity'] = 1;
        }
        if(count($toiletStaffs)>0){
            $toiletStaffs[0]['quantity'] = 1;
        }
        if(count($cleaners)>0){
            $cleaners[0]['quantity'] = 1;
        }
        if(count($barStaff)>0){
            $barStaff[0]['quantity'] = 1;
        }
        if(count($other)>0){
            $other[0]['quantity'] = 1;
        }

        $waiterPrice = 0;
        $venueManagersPrice = 0;
        $toiletStaffsPrice = 0;
        $cleanersPrice = 0;
        $barStaffPrice = 0;
        $otherPrice = 0;


        //calculate the price
        $staff_arr = explode('|', $quote->staff_ids);

        // Extract option IDs and values
        $optionIds = explode('|', $quote->options_ids);
        $optionValues = explode('|', $quote->options_values);

        // Fetch the selected options based on the extracted IDs
        $selectedOptions = Option::whereIn('id', $optionIds)->get();
        $staff_price_arr = explode('|', $quote->staff_individual_prices);
        $staff_quantity_arr = explode('|', $quote->staff_individual_count);
        $i = 0;
        $flag = false;
        for($index = 0;$index < 6;$index + 1) {
            $staff_arr_val = isset($staff_arr[$index]) ? $staff_arr[$index] : null;
            foreach($staff_individual_ids as $staff_individual_id) {
                if($staff_arr_val == $staff_individual_id) {
                    $flag = true;
                }
            }
            if($flag) {
                $staff_price = Price::where('staff_id', $staff_arr_val)->get();
                $staff_items = Staffs::where('id', $staff_arr_val)->get();
                if($staff_arr_val > 0) {
                    $multiplierType = $staff_price[0]['multiplier'];
                    $selected_date_from = explode('-', $quote->date_from);
                    $selected_date_to = explode('-', $quote->date_to);
                    $selected_date_between = Carbon::parse($quote->date_to)->diffInDays(Carbon::parse($quote->date_from));
                    $selected_date_between = $selected_date_between == 0 ? 1 : $selected_date_between;
                    switch ($multiplierType) {
                        case 'daily':
                            if ($index == 0) {
                                $waiterPrice = $staff_price_arr[$i];
                                $waiter[0]['quantity'] = $staff_quantity_arr[$i];
                                $i += 1;
                            } else if ($index == 1) {
                                $venueManagersPrice = $staff_price_arr[$i];
                                $venueManagers[0]['quantity'] = $staff_quantity_arr[$i];
                                $i += 1;
                            } else if ($index == 2) {
                                $toiletStaffsPrice = $staff_price_arr[$i];
                                $toiletStaffs[0]['quantity'] = $staff_quantity_arr[$i];
                                $i += 1;
                            } else if ($index == 3) {
                                $cleanersPrice = $staff_price_arr[$i];
                                $cleaners[0]['quantity'] = $staff_quantity_arr[$i];
                                $i += 1;
                            } else if ($index == 4) {
                                $barStaffPrice = $staff_price_arr[$i];
                                $barStaff[0]['quantity'] = $staff_quantity_arr[$i];
                                $i += 1;
                            } else if ($index == 5) {
                                $otherPrice = $staff_price_arr[$i];
                                $other[0]['quantity'] = $staff_quantity_arr[$i];
                                $i += 1;
                            }
                            break;
                        case 'hourly':
                            $dateFromC = Carbon::createFromFormat('d-m-Y', $quote->date_from);
                            $currentDate = $dateFromC->copy();
                            $dateFrom = Carbon::createFromFormat('d-m-Y', $quote->date_from);
                            $dateTo = Carbon::createFromFormat('d-m-Y', $quote->date_to);
    
                            $timeFrom = $quote->time_from;
                            $timeTo = $quote->time_to;
                            $hours = $this->calculateNumberOfHours($currentDate, $timeFrom, $currentDate, $timeTo);
                            switch($index) {
                                case 0:
                                    $waiterPrice = $staff_price_arr[$i];
                                    $waiter[0]['quantity'] = $staff_quantity_arr[$i];
                                    $i += 1;
                                    break;
                                case 1:
                                    $venueManagersPrice = $staff_price_arr[$i];
                                    $venueManagers[0]['quantity'] = $staff_quantity_arr[$i];
                                    $i += 1;
                                    break;
                                case 2:
                                    $toiletStaffsPrice = $staff_price_arr[$i];
                                    $toiletStaffs[0]['quantity'] = $staff_quantity_arr[$i];
                                    $i += 1;
                                    break;
                                case 3:
                                    $cleanersPrice = $staff_price_arr[$i];
                                    $cleaners[0]['quantity'] = $staff_quantity_arr[$i];
                                    $i += 1;
                                    break;
                                case 4:
                                    $barStaffPrice = $staff_price_arr[$i];
                                    $barStaff[0]['quantity'] = $staff_quantity_arr[$i];
                                    $i += 1;
                                    break;
                                case 5:
                                    $otherPrice = $staff_price_arr[$i];
                                    $other[0]['quantity'] = $staff_quantity_arr[$i];
                                    $i += 1;
                                    break;
                            }
                            break;
                        case 'event':
                            switch($index) {
                                case 0:
                                    $waiterPrice = $staff_price_arr[$i];
                                    $waiter[0]['quantity'] = 1;
                                    $i += 1;
                                    break;
                                case 1:
                                    $venueManagersPrice = $staff_price_arr[$i];
                                    if($venueManagersPrice > 0) $venueManagers[0]['quantity'] = 1;
                                    $i += 1;
                                    break;
                                case 2:
                                    $toiletStaffsPrice = $staff_price_arr[$i];
                                    if($toiletStaffsPrice > 0) $toiletStaffs[0]['quantity'] = 1;
                                    $i += 1;
                                    break;
                                case 3:
                                    $cleanersPrice = $staff_price_arr[$i];
                                    if($cleanersPrice > 0) $cleaners[0]['quantity'] = 1;
                                    $i += 1;
                                    break;
                                case 4:
                                    $barStaffPrice = $staff_price_arr[$i];
                                    if($barStaffPrice > 0) $barStaff[0]['quantity'] = 1;
                                    $i += 1;
                                    break;
                                case 5:
                                    $otherPrice = $staff_price_arr[$i];
                                    if($otherPrice > 0) $other[0]['quantity'] = 1;
                                    $i += 1;
                                    break;
                            }
                            break;
                        case 'event_pp':
                            $people = $quote->people;
                            if ($index == 0 && $staff_arr[$index + 6] !== 'null') {
                                $waiterPrice = $staff_price_arr[$i];
                                $waiter[0]['quantity'] = explode(',', $staff_items[0]['count'])[$staff_arr[$index + 6]];
                                $i += 1;
                            } else if ($index == 1 && $staff_arr[$index + 6] !== 'null') {
                                $venueManagersPrice = $staff_price_arr[$i]; 
                                $venueManagers[0]['quantity'] = explode(',',$staff_items[0]['count'])[$staff_arr[$index + 6]];
                                $i += 1;
                            } else if ($index == 2 && $staff_arr[$index + 6] !== 'null') {
                                $toiletStaffsPrice = $staff_price_arr[$i];
                                $toiletStaffs[0]['quantity'] = explode(',',$staff_items[0]['count'])[$staff_arr[$index + 6]];
                                $i += 1;
                            } else if($index == 3 && $staff_arr[$index + 6] !== 'null') {
                                $cleanersPrice = $staff_price_arr[$i];
                                $cleaners[0]['quantity'] = explode(',',$staff_items[0]['count'])[$staff_arr[$index + 6]];
                                $i += 1;
                            } else if($index == 4 && $staff_arr[$index + 5] !== 'null') {
                                $barStaffPrice = $staff_price_arr[$i];
                                $barStaff[0]['quantity'] = explode(',',$staff_items[0]['count'])[$staff_arr[$index + 6]];
                                $i += 1;
                            } else if($index == 5 && $staff_arr[$index + 6] !== 'null') {
                                $otherPrice = $staff_price_arr[$i];
                                $other[0]['quantity'] = explode(',',$staff_items[0]['count'])[$staff_arr[$index + 6]];
                                $i += 1;
                            }
                            break;
                    }
                }
            }
            $index++;
            $flag = false;
        }

        // Combine the selected options with their values
        $optionsWithValues = [];

        foreach ($selectedOptions as $index => $selectedOption) {
            $optionsWithValues[] = [
                'option' => $selectedOption,
                'value'  => $optionValues[$index] ?? null, // Using null as a default if there's no corresponding value
                'type'   => $selectedOption->type
            ];
        }

        if($quote->options_name) {
            foreach($optionsWithValues as $index => $optionsWithValue) {
                $optionsWithValue['option']->name = explode('|', $quote->options_name)[$index];
            }
        }

        $allSeasons = Season::orderBy('priority', 'desc')->where('tenant_id', $quote->tenant_id)->get();

        // Initialize variables to keep track of the highest priority season
        $highestPrioritySeason = null;
        $highestPriority = -1;

        $associatedSeason = $highestPrioritySeason;

        // Get the tenant ID from the quote
        $tenantId = $quote->tenant_id;

        // Fetch the tenant model using the tenant ID
        $tenant = Tenant::find($tenantId);

        $hashids = new Hashids('em-and-georg-are-supercool');
        $hashedId = $hashids->encode($quote->id);

        view()->share('quote', $quote);
        view()->share('hashedId', $hashedId);
        return view('pages.quotes.show', compact('extraItemsName', 'extraItemsCount', 'extraItemsPrice', 'relatedQuotes', 'discount', 'associatedContact', 'associatedSeason', 'optionsWithValues', 'tenant', 'waiter', 'venueManagers', 'toiletStaffs', 'cleaners', 'waiterPrice', 'venueManagersPrice', 'toiletStaffsPrice', 'cleanersPrice', 'barStaff', 'barStaffPrice', 'other', 'otherPrice', 'details' ), ['hashedId' => $hashedId]);
    }

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

    public function showPublic($hashedId, Contact $contact, Season $season, Option $option)
    {
        // Decode the hashedId to get the original quote ID
        $hashids = new Hashids('em-and-georg-are-supercool');
        $quoteId = $hashids->decode($hashedId)[0];

        // Fetch the Quote model using the original ID
        $quote = Quote::findOrFail($quoteId);

        // Get the quote_number from the current $quote object
        $quote_number = $quote->quote_number;
        $contact_id = $quote->contact_id;

        // Get the date_from and date_to values from the $quote model
        $dateFrom = $quote->date_from;
        $dateTo = $quote->date_to;

        $discount = $quote->discount;

        // Fetch related quotes based on the determined quote_number
        $relatedQuotes = Quote::where('quote_number', $quote_number)
            ->orderBy('version')
            ->get();

        $associatedContact = Contact::where('id', $contact_id)
            ->get();

        // Extract option IDs and values
        $optionIds = explode('|', $quote->options_ids);
        $optionValues = explode('|', $quote->options_values);

        // Fetch the selected options based on the extracted IDs
        $selectedOptions = Option::whereIn('id', $optionIds)->get();

        // Combine the selected options with their values
        $optionsWithValues = [];

        foreach ($selectedOptions as $index => $selectedOption) {
            $optionsWithValues[] = [
                'option' => $selectedOption,
                'value'  => $optionValues[$index] ?? null, // Using null as a default if there's no corresponding value
                'type'   => $selectedOption->type
            ];
        }

        $allSeasons = Season::orderBy('priority', 'desc')->where('tenant_id', $quote->tenant_id)->get();

        // Initialize variables to keep track of the highest priority season
        $highestPrioritySeason = null;
        $highestPriority = -1;

        // Iterate through the seasons to find the one with the highest priority
        foreach ($allSeasons as $season) {
            $seasonStartDate = Carbon::createFromFormat('d-m-Y', $season->date_from);
            $seasonEndDate = Carbon::createFromFormat('d-m-Y', $season->date_to);

            // Check if the date range falls within this season
            $isWithinSeason = Carbon::createFromFormat('d-m-Y', $dateFrom)
                ->between($seasonStartDate, $seasonEndDate) ||
                Carbon::createFromFormat('d-m-Y', $dateTo)
                ->between($seasonStartDate, $seasonEndDate) ||
                (Carbon::createFromFormat('d-m-Y', $dateFrom) <= $seasonStartDate &&
                    Carbon::createFromFormat('d-m-Y', $dateTo) >= $seasonEndDate);

            if ($isWithinSeason && $season->priority > $highestPriority) {
                // Update the highest priority season
                $highestPrioritySeason = $season;
                $highestPriority = $season->priority;
            }
        }

        $tenantId = $quote->tenant_id;

        // Fetch the tenant model using the tenant ID
        $tenant = Tenant::find($tenantId);

        $associatedSeason = $highestPrioritySeason;

        view()->share('quote', $quote);

        return view('pages.quotes.showPublic', compact('relatedQuotes', 'discount', 'associatedContact', 'associatedSeason', 'optionsWithValues', 'tenant'));
    }

}
