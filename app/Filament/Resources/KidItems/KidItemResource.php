<?php

namespace App\Filament\Resources\KidItems;

use App\Filament\Resources\Concerns\UsesManagePermission;

use App\Filament\Resources\KidItems\Pages\CreateKidItem;
use App\Filament\Resources\KidItems\Pages\EditKidItem;
use App\Filament\Resources\KidItems\Pages\ListKidItems;
use App\Filament\Resources\KidItems\Schemas\KidItemForm;
use App\Filament\Resources\KidItems\Tables\KidItemsTable;
use App\Models\KidItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KidItemResource extends Resource
{
    use UsesManagePermission;

    protected static ?string $model = KidItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Thực đơn';

    protected static ?string $navigationLabel = 'Món trẻ em';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'Kid Item';

    public static function form(Schema $schema): Schema
    {
        return KidItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KidItemsTable::configure($table);
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
            'index' => ListKidItems::route('/'),
            'create' => CreateKidItem::route('/create'),
            'edit' => EditKidItem::route('/{record}/edit'),
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
