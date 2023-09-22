<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Quote
 *
 * @package App\Models
 * @property int id
 * @property int contact_id
 * @property string status
 * @property string version
 * @property string date_from
 * @property string date_to
 * @property string time_from
 * @property string time_to
 * @property int area_id
 * @property string event_type
 * @property-read Contact contact
 * @property-read Area area
 * @property-read \Illuminate\Support\Carbon created_at
 * @property-read \Illuminate\Support\Carbon updated_at
 */
class Quote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'contact_id',
        'status',
        'version',
        'date_from',
        'date_to',
        'time_from',
        'time_to',
        'area_id',
        'event_type',
    ];

    /**
     * Get the contact associated with the quote.
     *
     * @return BelongsTo
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the area associated with the quote.
     *
     * @return BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class); // Adjust the namespace if Area is in a different namespace
    }
}
