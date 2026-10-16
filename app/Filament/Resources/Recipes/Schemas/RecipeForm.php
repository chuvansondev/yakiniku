<?php

namespace App\Filament\Resources\Recipes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('short_description')
                    ->default(null),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('status')
                    ->required(),
                DateTimePicker::make('published_at'),
                TextInput::make('delete_at')
                    ->numeric()
                    ->default(null),
                TextInput::make('delete_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
