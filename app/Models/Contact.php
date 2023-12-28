<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'name',
        'email',
        'phone',
        'address',
        'postcode',
        'city',
        'state',
        'country',
        'notes',
    ];

    public function tenant(): BelongsTo
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
