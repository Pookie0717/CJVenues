<?php

namespace App\Http\Controllers;

//use App\DataTables\PricesDataTable;

class PricesController extends Controller
{
    public function index()
    {
        return view('pages.prices.prices');;
    }
    // ... other methods
}
