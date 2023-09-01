<?php

namespace App\Http\Controllers;

use App\DataTables\QuotesDataTable;

class QuotesController extends Controller
{
    public function index(QuotesDataTable $dataTable)
    {
        return $dataTable->render('pages.quotes.quotes');
    }

    // ... other methods
}
