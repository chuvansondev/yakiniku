<?php

namespace App\Http\Requests;

use App\Services\BookingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $restaurantId = $this->input('restaurant_id');
        $tableCodes = $restaurantId && is_numeric($restaurantId)
            ? app(BookingService::class)->tableCapacities((int) $restaurantId)
            : [];

        if ($this->user()) {
            $this->merge([
                'customer_name' => $this->user()->name,
                'email' => $this->user()->email,
            ]);
        }

        return [
            'restaurant_id' => ['required', 'integer', Rule::exists('restaurants', 'id')->where('status', true)],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'number_of_guests' => ['required', 'integer', 'between:1,100'],
            'terms_accepted' => ['accepted'],
            'table_codes' => ['required', 'array', 'min:1'],
            'table_codes.*' => ['string', 'distinct', Rule::in(array_keys($tableCodes))],
            'note' => ['nullable', 'string', 'max:2000'],
            'pre_order_items' => ['nullable', 'array'],
            'pre_order_items.*' => ['integer', 'distinct', Rule::exists('menu_items', 'id')->where('status', true)->where('is_must_try', true)],
        ];
    }
}
