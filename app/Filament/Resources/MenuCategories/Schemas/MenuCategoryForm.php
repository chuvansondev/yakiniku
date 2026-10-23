<?php

namespace App\Filament\Resources\MenuCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('name_en')
                    ->label(__('Name (English)')),
                TextInput::make('slug')
                    ->nullable()
                    ->maxLength(255)
                    ->helperText(__('Để trống để tự tạo slug từ tên danh mục.')),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('description_en')
                    ->label(__('Description (English)'))
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->nullable()
                    ->helperText(__('Để trống để tự tăng thứ tự.')),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
