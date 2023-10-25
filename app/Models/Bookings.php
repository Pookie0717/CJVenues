<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
    ];
    protected static function boot() {
        parent::boot();

        self::creating(function($model) {
            $currentTenantId = Session::get('current_tenant_id');
            $model->tenant_id = $currentTenantId;
        });
    }
}
