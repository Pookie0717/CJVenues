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
        'parent_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function parent()
    {
        return $this->hasOne(Tenant::class, 'id', 'parent_id');
    }

    public function isMain()
    {
        return $this->parent_id === null;
    }
}
