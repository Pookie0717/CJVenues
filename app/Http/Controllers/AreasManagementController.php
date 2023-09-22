<?php

namespace App\Http\Controllers;

use App\DataTables\AreasDataTable;

class AreasManagementController extends Controller
{
    public function index(AreasDataTable $dataTable)
    {
        return $dataTable->render('pages.areas.areas');
    }

    // ... other methods
}