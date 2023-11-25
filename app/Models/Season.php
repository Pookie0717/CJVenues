<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date_from',
        'date_to',
        'priority',
        'weekdays',
        'tenant_id',
    ];
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
    public function options()
    {
        return $this->hasMany(Option::class, 'season_id');
    }

    public static function getAllSeason()
    {
        $currentTenantId = Session::get('current_tenant_id');
        return static::where('name', 'All')->where('tenant_id', $currentTenantId)->first();
    }
}
