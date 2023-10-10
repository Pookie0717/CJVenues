<?php

namespace App\Http\Controllers;

use App\DataTables\QuotesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
    public function index(QuotesDataTable $dataTable)
    {
        return $dataTable->render('pages.quotes.quotes');
    }

  /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        // Get the quote_number from the current $quote object
        $quote_number = $quote->quote_number;

        // Fetch related quotes based on the determined quote_number
        $relatedQuotes = Quote::where('quote_number', $quote_number)
            ->orderBy('version')
            ->get();

        view()->share('quote', $quote);

        return view('pages.quotes.show', compact('relatedQuotes'));
    }

}
