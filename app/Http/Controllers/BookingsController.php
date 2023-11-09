<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Carbon\Carbon;

class BookingsController extends Controller
{
    public function index()
    {
        // Retrieve all quotes with a 'booked' status
        $bookedQuotes = Quote::where('status', 'booked')->get()
                             ->map(function ($quote) {

                                 return [
                                     'id' => $quote->id,
                                     'title' => $quote->event_name,
                                     // Format dates for FullCalendar
                                     'start' => Carbon::createFromFormat('d-m-Y', $quote->date_from)->format('Y-m-d'),
                                     'end' => Carbon::createFromFormat('d-m-Y', $quote->date_to)->format('Y-m-d'),
                                 ];
                             });

        return view('pages.bookings.index', ['bookedQuotes' => $bookedQuotes]);
    }
}
