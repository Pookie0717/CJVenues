<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use App\Models\Venue; // Ensure the Venue model is imported

class VenueArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'capacity_noseating',
        'capacity_seatingrows',
        'capacity_seatingtables',
    ];

    // Define the relationship with the Venue model
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'area_id');
    }
    protected static function boot() {
        parent::boot();

        self::creating(function($model) {
            $currentTenantId = Session::get('current_tenant_id');
            $model->tenant_id = $currentTenantId;
        });
    }

}
