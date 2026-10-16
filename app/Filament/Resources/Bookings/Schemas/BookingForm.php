<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_code')
                    ->required(),
                Select::make('restaurant_id')
                    ->relationship('restaurant', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->default(null),
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                DatePicker::make('booking_date')
                    ->required(),
                TimePicker::make('booking_time')
                    ->required(),
                TextInput::make('number_of_guests')
                    ->required()
                    ->numeric(),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
        ])
                    ->default('pending')
                    ->required(),
                TextInput::make('qr_code')
                    ->default(null),
                TextInput::make('delete_at')
                    ->numeric()
                    ->default(null),
                TextInput::make('delete_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
