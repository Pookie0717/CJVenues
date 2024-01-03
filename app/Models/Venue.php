<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'address',
        'postcode',
        'city',
        'state',
        'country',
    ];

    public function areas()
        {
            return $this->hasMany(VenueArea::class);
        }
    public function prices()
    {
        return $this->hasMany(Price::class, 'venue_id');
    }
    public function options()
    {
        return $this->hasMany(Option::class, 'venue_id');
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
