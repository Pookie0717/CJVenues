<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id', // Add 'area_id' to the fillable array
        'start_date',
        'end_date',
    ];

    // Your other model code...
}
