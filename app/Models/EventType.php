<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'typical_seating',
        'duration_type',
        'duration',
        'min_duration',
        'time_setup',
        'time_cleaningup',
        'season_id',
        'availability',
    ];
}
