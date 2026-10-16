<?php

namespace App\Filament\Resources\Restaurants\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RestaurantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('latitude')
                    ->numeric()
                    ->default(null),
                TextInput::make('longitude')
                    ->numeric()
                    ->default(null),
                TextInput::make('google_map_url')
                    ->url()
                    ->default(null),
                TimePicker::make('opening_time'),
                TimePicker::make('closing_time'),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('status')
                    ->required(),
                TextInput::make('delete_at')
                    ->numeric()
                    ->default(null),
                TextInput::make('delete_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
