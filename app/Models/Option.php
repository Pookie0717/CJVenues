<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'type',
        'values',
    ];

    public function prices()
    {
        return $this->hasMany(Price::class, 'option_id');
    }
}
