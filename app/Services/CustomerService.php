<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerService
{
    public function claimGuestBookingsForVerifiedUser(User $user): void
    {
        $emailNormalized = Str::lower(trim($user->email));

        DB::transaction(function () use ($user, $emailNormalized): void {
            $customers = Customer::query()
                ->where('email_normalized', $emailNormalized)
                ->lockForUpdate()
                ->get();

            // Ambiguous legacy identities must not be attached to an account.
            if ($customers->count() !== 1) {
                return;
            }

            $customer = $customers->first();
            if ($customer->user_id && (int) $customer->user_id !== (int) $user->id) {
                return;
            }

            if (! $customer->user_id) {
                $customer->user_id = $user->id;
                $customer->save();
            }

            $customer->bookings()->whereNull('user_id')->update(['user_id' => $user->id]);
        });
    }

    public function resolve(string $name, ?string $email, ?string $phone, ?int $userId = null, string $source = 'booking'): Customer
    {
        $email = filled($email) ? trim($email) : null;
        $phone = filled($phone) ? trim($phone) : null;
        $emailNormalized = $email ? Str::lower($email) : null;
        $phoneNormalized = $phone ? preg_replace('/\D+/', '', $phone) : null;
        $phoneNormalized = $phoneNormalized !== '' ? $phoneNormalized : null;

        if (! $userId && ! $emailNormalized && ! $phoneNormalized) {
            return Customer::query()->create([
                'name' => $name,
                'source' => $source,
                'last_contact_at' => now(),
                'status' => 'active',
            ]);
        }

        $matches = Customer::query()
            ->where(function ($identity) use ($userId, $emailNormalized, $phoneNormalized): void {
                if ($userId) $identity->orWhere('user_id', $userId);
                if ($emailNormalized) $identity->orWhere('email_normalized', $emailNormalized);
                if ($phoneNormalized) $identity->orWhere('phone_normalized', $phoneNormalized);
            })
            ->orderBy('id')
            ->get();

        if ($matches->count() > 1) {
            throw ValidationException::withMessages([
                'email' => __('Email hoặc số điện thoại đang khớp với nhiều hồ sơ khách hàng. Vui lòng liên hệ nhà hàng để được hỗ trợ.'),
            ]);
        }

        $customer = $matches->first() ?? new Customer(['source' => $source]);

        $customer->fill([
            'user_id' => $customer->user_id ?: $userId,
            'name' => $name ?: $customer->name,
            'email' => $email ?: $customer->email,
            'email_normalized' => $emailNormalized ?: $customer->email_normalized,
            'phone' => $phone ?: $customer->phone,
            'phone_normalized' => $phoneNormalized ?: $customer->phone_normalized,
            'last_contact_at' => now(),
            'status' => 'active',
        ])->save();

        return $customer;
    }
}
