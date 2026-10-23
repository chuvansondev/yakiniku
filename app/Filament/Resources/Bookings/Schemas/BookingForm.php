<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use App\Enums\BookingStatus;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_code')
                    ->label(__('Booking code'))
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder(__('Automatically generated when saved')),
                Select::make('restaurant_id')->label(__('Restaurant'))
                    ->relationship('restaurant', 'name')
                    ->required(),
                Select::make('user_id')->label(__('User'))
                    ->relationship('user', 'name')
                    ->default(null),
                Select::make('customer_id')->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->default(null),
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label(__('Email address'))
                    ->email()
                    ->default(null),
                DatePicker::make('booking_date')
                    ->required(),
                TimePicker::make('booking_time')
                    ->seconds(false)
                    ->required(),
                TextInput::make('number_of_guests')
                    ->required()
                    ->numeric(),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(collect(BookingStatus::cases())->mapWithKeys(fn (BookingStatus $status): array => [$status->value => $status->label()])->all())
                    ->default('pending')
                    ->required(),
                TextInput::make('qr_code')
                    ->default(null),
            ]);
    }
}
