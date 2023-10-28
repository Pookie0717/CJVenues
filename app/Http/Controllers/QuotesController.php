<?php

namespace App\Http\Controllers;

use App\DataTables\QuotesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\Season;
use App\Models\Option;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Hashids\Hashids;

class QuotesController extends Controller
{
    public function index(QuotesDataTable $dataTable)
    {
        return $dataTable->render('pages.quotes.quotes');
    }

  /**
     * Display the specified resource.
     */
    public function show(Quote $quote, Contact $contact, Season $season, Option $option)
    {
        // Get the quote_number from the current $quote object
        $quote_number = $quote->quote_number;
        $contact_id = $quote->contact_id;

        // Get the date_from and date_to values from the $quote model
        $dateFrom = $quote->date_from;
        $dateTo = $quote->date_to;

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

        $allSeasons = Season::orderBy('priority', 'desc')->get();

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

        $associatedSeason = $highestPrioritySeason;

        // Get the tenant ID from the quote
        $tenantId = $quote->tenant_id;

        // Fetch the tenant model using the tenant ID
        $tenant = Tenant::find($tenantId);

        $hashids = new Hashids('em-and-georg-are-supercool');
        $hashedId = $hashids->encode($quote->id);

        view()->share('quote', $quote);
        view()->share('hashedId', $hashedId);

        return view('pages.quotes.show', compact('relatedQuotes', 'associatedContact', 'associatedSeason', 'optionsWithValues', 'tenant'), ['hashedId' => $hashedId]);
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

        $allSeasons = Season::orderBy('priority', 'desc')->get();

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

        // Get the tenant ID from the quote
        $tenantId = $quote->tenant_id;

        // Fetch the tenant model using the tenant ID
        $tenant = Tenant::find($tenantId);

        $associatedSeason = $highestPrioritySeason;

        view()->share('quote', $quote);

        return view('pages.quotes.showPublic', compact('relatedQuotes', 'associatedContact', 'associatedSeason', 'optionsWithValues', 'quote', 'tenant'));
    }



}
