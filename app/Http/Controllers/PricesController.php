<?php

namespace App\Http\Controllers;

use App\DataTables\PricesDataTable;

class PricesController extends Controller
{
    public function index(PricesDataTable $dataTable)
    {
        return $dataTable->render('pages.prices.prices');
    }
    // ... other methods
}
