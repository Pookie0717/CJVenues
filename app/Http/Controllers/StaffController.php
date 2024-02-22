<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\StaffDataTable;

class StaffController extends Controller
{
    public function index(staffDataTable $dataTable)
    {
        return $dataTable->render('pages.staff.staff');
    }

    // ... other methods

}
