<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'quote_number',
        'version',
        'date_from',
        'date_to',
        'time_from',
        'time_to',
        'area_id',
        'event_type',
        'event_name',
        'calculated_price',
        'discount_type',
        'discount',
        'price',
        'price_options',
        'price_venue',
        'options_ids',
        'options_values',
        'people',
        'buffer_time_before',
        'buffer_time_after',
        'buffer_time_unit',
    ];
    protected static function boot() {
        parent::boot();

        self::creating(function($model) {
            $currentTenantId = Session::get('current_tenant_id');
            $model->tenant_id = $currentTenantId;
        });
    }

    /**
     * Get the contact associated with the quote.
     *
     * @return BelongsTo
     */
    public function eventContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * Get the area associated with the quote.
     *
     * @return BelongsTo
     */
    public function eventArea(): BelongsTo
    {
        return $this->belongsTo(VenueArea::class, 'area_id');
    }
    /**
     * Get the event associated with the quote.
     *
     * @return BelongsTo
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class, 'event_type');
    }
}
