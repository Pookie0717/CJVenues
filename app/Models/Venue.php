<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'postcode',
        'city',
        'state',
        'country',
    ];

    public function areas()
        {
            return $this->hasMany(VenueArea::class);
        }
    public function prices()
    {
        return $this->hasMany(Price::class, 'venue_id');
    }
}
