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
    public function index(QuotesDataTable $dataTable)
    {
        return $dataTable->render('pages.quotes.quotes');
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

        $discount = $quote->discount;

        // Fetch related quotes based on the determined quote_number
        $relatedQuotes = Quote::where('quote_number', $quote_number)
            ->orderBy('version')
            ->get();

        $associatedContact = Contact::where('id', $contact_id)
            ->get();

        $staffIds = explode('|', $quote->staff_ids);
        if(count($staffIds) <= 1) {
            $staffIds = [0, 0, 0, 0];
        }
        $waiter = Staffs::where('id', $staffIds[0])->get();
        $venueManagers = Staffs::where('id', $staffIds[1])->get();
        $toiletStaffs = Staffs::where('id', $staffIds[2])->get();
        $cleaners = Staffs::where('id', $staffIds[3])->get();

        if(!isset($waiter)) {
            $waiter[0]['quantity'] = 1;
        }
        if(!isset($venueManagers)){
            $venueManagers[0]['quantity'] = 1;
        }
        if(!isset($toiletStaffs)){
            $toiletStaffs[0]['quantity'] = 1;
        }
        if(!isset($cleaners)){
            $cleaners[0]['quantity'] = 1;
        }

        $waiterPrice = 0;
        $venueManagersPrice = 0;
        $toiletStaffsPrice = 0;
        $cleanersPrice = 0;

        //calculate the price
        $staff_arr = explode('|', $quote->staff_ids);
        for($index = 0;$index < 4;$index + 1) {
            $staff_arr_val = isset($staff_arr[$index]) ? $staff_arr[$index] : null;
            $staff_price = Price::where('staff_id', $staff_arr_val)->get();
            $staff_items = Staffs::where('id', $staff_arr_val)->get();
            if($staff_price->count() > 0) {
                $multiplierType = $staff_price[0]['multiplier'];
                $selected_date_from = explode('-', $quote->date_from);
                $selected_date_to = explode('-', $quote->date_to);
                $selected_date_between = Carbon::parse($quote->date_to)->diffInDays(Carbon::parse($quote->date_from));
                switch ($multiplierType) {
                    case 'daily':
                        $staff_price[0]['price'] = $staff_price[0]['price'] * $selected_date_between;
                        if ($index == 0) {
                            $waiterPrice = $staff_price[0]['price'];
                            $waiter[0]['quantity'] = $selected_date_between;
                        } else if ($index == 1) {
                            $venueManagersPrice = $staff_price[0]['price'];
                            $venueManagers[0]['quantity'] = $selected_date_between;
                        } else if ($index == 2) {
                            $toiletStaffsPrice = $staff_price[0]['price'];
                            $toiletStaffs[0]['quantity'] = $selected_date_between;
                        }else {
                            $cleanersPrice = $staff_price[0]['price'];
                            $cleaners[0]['quantity'] = $selected_date_between;
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
                        Log::info($hours);
                        if ($index == 0) {
                            $waiterPrice = $staff_price[0]['price'] * $hours;
                            $waiter[0]['quantity'] = $hours;
                        }
                        else if ($index == 1) {
                            $venueManagersPrice = $staff_price[0]['price'] * $hours;
                            $venueManagers[0]['quantity'] = $hours;
                        }
                        else if ($index == 2) {
                            $toiletStaffsPrice = $staff_price[0]['price'] * $hours;
                            $toiletStaffs[0]['quantity'] = $hours;
                        }
                        else {
                            $cleanersPrice = $staff_price[0]['price'] * $hours;
                            $cleaners[0]['quantity'] = $hours;
                        }
                        break;
                    case 'event':
                        $quantity[$index] = 1;
                        if ($index == 0) {
                            $waiterPrice = $staff_price[0]['price'];
                            $waiter[0]['quantity'] = 1;
                        } else if ($index == 1) {
                            $venueManagersPrice = $staff_price[0]['price'];
                            $venueManagers[0]['quantity'] = 1;
                        }
                        else if ($index == 2) {
                            $toiletStaffsPrice = $staff_price[0]['price'];
                            $toiletStaffs[0]['quantity'] = 1;
                        }
                        else {
                            $cleanersPrice = $staff_price[0]['price'];
                            $cleaners[0]['quantity'] = 1;
                        } 
                        break;
                    case 'event_pp':
                        if ($index == 0 && $staff_arr[$index + 4] !== 'null') {
                            $waiterPrice = $staff_price[0]['price'] * explode(',', $staff_items[0]['count'])[$staff_arr[$index + 4]];
                            $waiter[0]['quantity'] = explode(',', $staff_items[0]['count'])[$staff_arr[$index + 4]];
                        } else if ($index == 1 && $staff_arr[$index + 4] !== 'null') {
                            $venueManagersPrice = $staff_price[0]['price'] * explode(',',$staff_items[0]['count'])[$staff_arr[$index + 4]]; 
                            $venueManagers[0]['quantity'] = explode(',',$staff_items[0]['count'])[$staff_arr[$index + 4]];
                        } else if ($index == 2 && $staff_arr[$index + 4] !== 'null') {
                            $toiletStaffsPrice = $staff_price[0]['price'] * explode(',',$staff_items[0]['count'])[$staff_arr[$index + 4]];
                            $toiletStaffs[0]['quantity'] = explode(',',$staff_items[0]['count'])[$staff_arr[$index + 4]];
                        } else if($index == 3 && $staff_arr[$index + 4] !== 'null') {
                            $cleanersPrice = $staff_price[0]['price'] * explode(',',$staff_items[0]['count'])[$staff_arr[$index + 4]];
                            $cleaners[0]['quantity'] = explode(',',$staff_items[0]['count'])[$staff_arr[$index + 4]];
                        }
                        break;
                }
            }
            $index++;
        }

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
        // foreach ($allSeasons as $season) {
        //     $seasonStartDate = Carbon::createFromFormat('d-m-Y', $season->date_from);
        //     $seasonEndDate = Carbon::createFromFormat('d-m-Y', $season->date_to);

        //     // Check if the date range falls within this season
        //     $isWithinSeason = Carbon::createFromFormat('d-m-Y', $dateFrom)
        //         ->between($seasonStartDate, $seasonEndDate) ||
        //         Carbon::createFromFormat('d-m-Y', $dateTo)
        //         ->between($seasonStartDate, $seasonEndDate) ||
        //         (Carbon::createFromFormat('d-m-Y', $dateFrom) <= $seasonStartDate &&
        //             Carbon::createFromFormat('d-m-Y', $dateTo) >= $seasonEndDate);

        //     if ($isWithinSeason && $season->priority > $highestPriority) {
        //         // Update the highest priority season
        //         $highestPrioritySeason = $season;
        //         $highestPriority = $season->priority;
        //     }
        // }

        $associatedSeason = $highestPrioritySeason;

        // Get the tenant ID from the quote
        $tenantId = $quote->tenant_id;

        // Fetch the tenant model using the tenant ID
        $tenant = Tenant::find($tenantId);

        $hashids = new Hashids('em-and-georg-are-supercool');
        $hashedId = $hashids->encode($quote->id);

        view()->share('quote', $quote);
        view()->share('hashedId', $hashedId);

        return view('pages.quotes.show', compact('relatedQuotes', 'discount', 'associatedContact', 'associatedSeason', 'optionsWithValues', 'tenant', 'waiter', 'venueManagers', 'toiletStaffs', 'cleaners', 'waiterPrice', 'venueManagersPrice', 'toiletStaffsPrice', 'cleanersPrice'), ['hashedId' => $hashedId]);
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
