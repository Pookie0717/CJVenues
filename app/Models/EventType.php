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
        'event_name',
        'duration_type',
        'duration',
        'min_duration',
        'time_setup',
        'time_cleaningup',
        'season_id',
        'availability',
    ];
    protected static function boot() {
        parent::boot();

        self::creating(function($model) {
            $currentTenantId = Session::get('current_tenant_id');
            $model->tenant_id = $currentTenantId;
        });
    }
}
