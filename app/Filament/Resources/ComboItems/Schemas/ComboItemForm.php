<?php

namespace App\Filament\Resources\ComboItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ComboItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('combo_id')->label(__('Combo'))
                    ->relationship('combo', 'name')
                    ->required(),
                Select::make('menu_item_id')->label(__('Menu item'))
                    ->relationship('menuItem', 'name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }
}
