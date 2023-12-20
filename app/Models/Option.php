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
        'season_ids',
        'venue_ids',
        'area_ids',
        'eventtype_ids',
        'logic',
        'description',
        'default_value',
        'vat',
        'always_included',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'option_id');
    }

    // Define the relationship for seasons and venues using the accessor/mutator
    public function seasons()
    {
        return Season::whereIn('id', $this->season_ids);
    }

    public function area()
    {
        return VenueArea::whereIn('id', $this->area_ids);
    }

    public function eventType()
    {
        return EventType::whereIn('name', $this->eventtype_ids);
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
