<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\VenueArea;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'typical_seating',
        'duration_type',
        'max_duration',
        'min_duration',
        'time_setup',
        'time_cleaningup',
        'event_name',
        'min_people',
        'max_people',
        'description',
        'tenant_id',
        'seasons',
        'opening_time',
        'closing_time',
        'availability',
        'venue_area_id',
        'min_buffer_before',
        'max_buffer_before',
        'min_buffer_after',
        'max_buffer_after',
    ];

    public function venueAreas()
    {
        return $this->hasMany(VenueArea::class, 'venue_area_id');
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
