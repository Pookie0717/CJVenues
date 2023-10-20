<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function setLocale($locale)
    {
        if (in_array($locale, ['en', 'de', 'it'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }

        return redirect()->back(); // Redirect back to the previous page
    }
}