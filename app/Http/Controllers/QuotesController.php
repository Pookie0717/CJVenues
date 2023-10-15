<?php

namespace App\Http\Controllers;

use App\DataTables\QuotesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Contact;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class QuotesController extends Controller
{
    public function index(QuotesDataTable $dataTable)
    {
        return $dataTable->render('pages.quotes.quotes');
    }

  /**
     * Display the specified resource.
     */
    public function show(Quote $quote, Contact $contact, Season $season)
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

        view()->share('quote', $quote);

        return view('pages.quotes.show', compact('relatedQuotes', 'associatedContact', 'associatedSeason'));
    }


}
