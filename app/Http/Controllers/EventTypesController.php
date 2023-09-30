<?php

namespace App\Http\Controllers;

use App\DataTables\EventTypesDataTable;

class EventTypesController extends Controller
{
    public function index(EventTypesDataTable $dataTable)
    {
        return $dataTable->render('pages.events.event-types');
    }

    // ... other methods
}