<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'type',
        'values',
        'season_ids', // Updated column name
        'venue_ids',  // Updated column name
        'logic',       // Added column
        'description', // Added column
        'default_value', // Added column
        'vat',         // Added column
        'always_included', // Added column
    ];

    public function prices()
    {
        return $this->hasMany(Price::class, 'option_id');
    }

    // Define the relationship for seasons and venues using the accessor/mutator
    public function seasons()
    {
        return Season::whereIn('id', $this->season_ids);
    }

    public function venues()
    {
        return Venue::whereIn('id', $this->venue_ids);
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $currentTenantId = Session::get('current_tenant_id');
            $model->tenant_id = $currentTenantId;
        });
    }
}
