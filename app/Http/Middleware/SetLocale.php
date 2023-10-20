<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Check if the user's session contains a locale
        if (Session::has('locale')) {
            // Set the application's locale to the value stored in the session
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}
