<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $currentTenantId = Session::get('current_tenant_id');
            $model->tenant_id = $currentTenantId;
        });
    }
}
