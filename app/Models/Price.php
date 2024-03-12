<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'value',
        'type',
        'venue_id',
        'area_id',
        'option_id',
        'staff_id',
        'price',
        'multiplier',
        'x',
        'season_ids',
        'extra_tier_type',
        'option_area_ids'
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
    public function stsaff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_ids');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
    // protected static function boot() {
    //     parent::boot();

    //     self::creating(function($model) {
    //         $currentTenantId = Session::get('current_tenant_id');
    //         $model->tenant_id = $currentTenantId;
    //     });
    // }
}
