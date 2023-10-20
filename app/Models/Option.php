<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'type',
        'values',
        'season_id',
        'venue_id',
    ];

    public function prices()
    {
        return $this->hasMany(Price::class, 'option_id');
    }
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }
}
