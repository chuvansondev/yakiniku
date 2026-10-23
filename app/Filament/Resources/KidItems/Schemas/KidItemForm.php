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
                    ->label(__('Name (English)'))
                    ->maxLength(255),
                TextInput::make('slug')
                    ->nullable()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText(__('Để trống để tự tạo từ tên món.')),
                Select::make('type')
                    ->options([
                        'food' => __('Food'),
                        'utensil' => __('Utensil'),
                    ])
                    ->required(),
                Select::make('food_category')
                    ->options([
                        'meat' => __('Thịt'),
                        'side_dish' => __('Món ăn kèm'),
                        'vegetable' => __('Rau củ'),
                        'soup' => __('Súp'),
                        'rice_noodles' => __('Cơm và mì'),
                        'dessert' => __('Tráng miệng'),
                    ])
                    ->nullable(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('description_en')
                    ->label(__('Description (English)'))
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('price')
                    ->numeric()
                    ->suffix('VND')
                    ->default(0)
                    ->required(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->nullable()
                    ->helperText(__('Để trống để tự tăng thứ tự.')),
                Toggle::make('status')
                    ->default(true)
                    ->required(),
            ]);
    }
}
