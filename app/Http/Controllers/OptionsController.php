<?php

namespace App\Http\Controllers;

use App\DataTables\OptionsDataTable;

class OptionsController extends Controller
{
    public function index(OptionsDataTable $dataTable)
    {
        return $dataTable->render('pages.options.options');
    }

    // ... other methods
}
