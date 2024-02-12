<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\staffDataTable;

class staffController extends Controller
{
    public function index(staffDataTable $dataTable)
    {
        return $dataTable->render('pages.staff.staff');
    }

    // ... other methods

}
