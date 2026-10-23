<?php

namespace App\Filament\Resources\Tips\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('title_en')
                    ->label(__('Title (English)'))
                    ->maxLength(255),
                TextInput::make('slug')
                    ->nullable()
                    ->helperText(__('Leave blank to generate it from the title.')),
                TextInput::make('short_description')
                    ->default(null),
                TextInput::make('short_description_en')
                    ->label(__('Short description (English)')),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('content_en')
                    ->label(__('Content (English)'))
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('status')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
