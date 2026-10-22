<?php

namespace App\Filament\Resources\KidItems\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KidItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_en')
                    ->label('Name (English)')
                    ->maxLength(255),
                TextInput::make('slug')
                    ->nullable()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Để trống để tự tạo từ tên món.'),
                Select::make('type')
                    ->options([
                        'food' => 'Food',
                        'utensil' => 'Utensil',
                    ])
                    ->required(),
                Select::make('food_category')
                    ->options([
                        'meat' => 'Thịt',
                        'side_dish' => 'Món ăn kèm',
                        'vegetable' => 'Rau củ',
                        'soup' => 'Súp',
                        'rice_noodles' => 'Cơm và mì',
                        'dessert' => 'Tráng miệng',
                    ])
                    ->nullable(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('description_en')
                    ->label('Description (English)')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->required(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->nullable()
                    ->helperText('Để trống để tự tăng thứ tự.'),
                Toggle::make('status')
                    ->default(true)
                    ->required(),
            ]);
    }
}
