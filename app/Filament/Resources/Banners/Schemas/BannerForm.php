<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->default(null),
                Select::make('type')
                    ->options(['image' => __('Image'), 'video' => __('Video')])
                    ->default('image')
                    ->required(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('video_url')
                    ->url()
                    ->default(null),
                TextInput::make('link')
                    ->default(null),
                TextInput::make('sort_order')
                    ->numeric()
                    ->nullable()
                    ->helperText(__('Để trống để tự tăng thứ tự.')),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
