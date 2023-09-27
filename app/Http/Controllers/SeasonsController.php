<?php

namespace App\Http\Controllers;

use App\DataTables\SeasonsDataTable;

class SeasonsController extends Controller
{
    public function index(SeasonsDataTable $dataTable)
    {
        return $dataTable->render('pages.seasons.seasons');
    }

    // ... other methods
}
