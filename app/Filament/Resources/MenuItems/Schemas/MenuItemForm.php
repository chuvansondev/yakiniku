<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('name_en')
                    ->label('Name (English)'),
                TextInput::make('slug')
                    ->nullable()
                    ->helperText('Để trống để tự tạo từ tên món.'),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('description_en')
                    ->label('Description (English)')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                Toggle::make('is_must_try')
                    ->required(),
                Toggle::make('is_for_kids')
                    ->required(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->nullable()
                    ->helperText('Để trống để tự tăng thứ tự.'),
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
