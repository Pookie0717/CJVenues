<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantUser extends Model
{
    protected $table = 'tenant_user';
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
    ];
}
