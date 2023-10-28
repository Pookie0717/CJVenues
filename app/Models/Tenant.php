<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'postcode',
        'stateprovince',
        'country',
        'currency',
        'vatnumber',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
