<?php

namespace App\Filament\Resources\Combos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ComboForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('original_price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                Toggle::make('status')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('delete_at')
                    ->numeric()
                    ->default(null),
                TextInput::make('delete_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
