<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'content'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
