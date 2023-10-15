<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venue; // Ensure the Venue model is imported

class VenueArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'capacity_noseating',
        'capacity_seatingrows',
        'capacity_seatingtables',
    ];

    // Define the relationship with the Venue model
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'area_id');
    }

}
