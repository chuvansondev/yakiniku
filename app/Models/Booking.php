<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'restaurant_id',
        'user_id',
        'customer_id',
        'create_by',
        'update_by',
        'floor',
        'table_codes',
        'customer_name',
        'phone',
        'email',
        'booking_date',
        'booking_time',
        'number_of_guests',
        'note',
        'pre_order_items',
        'status',
        'qr_code',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'floor' => 'integer',
        'table_codes' => 'array',
        'pre_order_items' => 'array',
        'completed_at' => 'datetime',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class)->latest('changed_at')->latest('id');
    }

}
