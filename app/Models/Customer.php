<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'email_normalized', 'phone', 'phone_normalized',
        'notes', 'source', 'last_contact_at', 'status',
    ];

    protected $casts = ['last_contact_at' => 'datetime'];

    protected static function booted(): void
    {
        static::saving(function (Customer $customer): void {
            $customer->email = filled($customer->email) ? trim($customer->email) : null;
            $customer->phone = filled($customer->phone) ? trim($customer->phone) : null;

            $customer->email_normalized = $customer->email
                ? Str::lower($customer->email)
                : null;

            $phoneNormalized = $customer->phone
                ? preg_replace('/\\D+/', '', $customer->phone)
                : null;
            $customer->phone_normalized = $phoneNormalized !== '' ? $phoneNormalized : null;

            $otherCustomers = static::query();
            if ($customer->exists) {
                $otherCustomers->whereKeyNot($customer->getKey());
            }

            if ($customer->email_normalized && (clone $otherCustomers)
                ->where('email_normalized', $customer->email_normalized)
                ->exists()) {
                throw ValidationException::withMessages([
                    'email' => __('This email is already linked to another customer.'),
                ]);
            }

            if ($customer->phone_normalized && (clone $otherCustomers)
                ->where('phone_normalized', $customer->phone_normalized)
                ->exists()) {
                throw ValidationException::withMessages([
                    'phone' => __('This phone number is already linked to another customer.'),
                ]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
