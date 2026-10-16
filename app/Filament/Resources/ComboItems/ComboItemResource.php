<?php

namespace App\Filament\Resources\ComboItems;

use App\Filament\Resources\ComboItems\Pages\CreateComboItem;
use App\Filament\Resources\ComboItems\Pages\EditComboItem;
use App\Filament\Resources\ComboItems\Pages\ListComboItems;
use App\Filament\Resources\ComboItems\Schemas\ComboItemForm;
use App\Filament\Resources\ComboItems\Tables\ComboItemsTable;
use App\Models\ComboItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ComboItemResource extends Resource
{
    protected static ?string $model = ComboItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Combo Item';

    public static function form(Schema $schema): Schema
    {
        return ComboItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComboItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComboItems::route('/'),
            'create' => CreateComboItem::route('/create'),
            'edit' => EditComboItem::route('/{record}/edit'),
        ];
    }
}
