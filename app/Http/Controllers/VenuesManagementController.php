<?php

namespace App\Http\Controllers;

use App\DataTables\VenuesDataTable;

class VenuesManagementController extends Controller
{
    public function index(VenuesDataTable $dataTable)
    {
        return $dataTable->render('pages.venues.venues');
    }

    // ... other methods
}