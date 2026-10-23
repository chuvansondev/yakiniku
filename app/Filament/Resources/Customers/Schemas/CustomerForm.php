<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')->label(__('User'))
                    ->relationship('user', 'name')
                    ->default(null),
                TextInput::make('name')
                    ->default(null),
                TextInput::make('email')
                    ->label(__('Email address'))
                    ->email()
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('source')
                    ->required()
                    ->default('booking'),
                DateTimePicker::make('last_contact_at'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}
