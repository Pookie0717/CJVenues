<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date_from',
        'date_to',
        'priority',
        'overwrite_weekday',
    ];
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
    public function options()
    {
        return $this->hasMany(Option::class, 'season_id');
    }
}
