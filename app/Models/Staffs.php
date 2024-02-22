<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staffs extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'area_ids',
        'tenant_id',
        'from',
        'to',
        'count',
        'duration_type'
    ];

    // Define relationships with other models if needed

    public function areas()
    {
        return VenueArea::whereIn('id', $this->area_ids);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
