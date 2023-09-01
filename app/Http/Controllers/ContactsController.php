<?php

namespace App\Http\Controllers;

use App\DataTables\ContactsDataTable;

class ContactsController extends Controller
{
    public function index(ContactsDataTable $dataTable)
    {
        return $dataTable->render('pages.contacts.contacts');
    }

    // ... other methods
}
