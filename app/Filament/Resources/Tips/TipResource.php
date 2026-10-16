<?php

namespace App\Filament\Resources\Tips;

use App\Filament\Resources\Tips\Pages\CreateTip;
use App\Filament\Resources\Tips\Pages\EditTip;
use App\Filament\Resources\Tips\Pages\ListTips;
use App\Filament\Resources\Tips\Schemas\TipForm;
use App\Filament\Resources\Tips\Tables\TipsTable;
use App\Models\Tip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipResource extends Resource
{
    protected static ?string $model = Tip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tip';

    public static function form(Schema $schema): Schema
    {
        return TipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipsTable::configure($table);
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
            'index' => ListTips::route('/'),
            'create' => CreateTip::route('/create'),
            'edit' => EditTip::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
