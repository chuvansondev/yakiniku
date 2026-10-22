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
                    ->nullable()
                    ->helperText('Để trống để tự tạo từ tên combo.'),
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
                    ->numeric()
                    ->nullable()
                    ->helperText('Để trống để tự tăng thứ tự.'),
            ]);
    }
}
