<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'type',
        'venue_id',
        'area_id',
        'option_id',
        'price',
        'multiplier',
        'season_id',
    ];

    // Define relationships with other models if needed

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function area()
    {
        return $this->belongsTo(VenueArea::class, 'area_id');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
    }
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
