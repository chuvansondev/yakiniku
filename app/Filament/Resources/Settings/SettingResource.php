<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Filament\Resources\Settings\Schemas\SettingForm;
use App\Models\Setting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|\UnitEnum|null $navigationGroup = 'Hệ thống';

    protected static ?string $navigationLabel = 'Cài đặt giao diện';

    protected static ?int $navigationSort = 1;


    protected static ?string $modelLabel = 'cài đặt giao diện';

    protected static ?string $pluralModelLabel = 'Cài đặt giao diện';

    protected static ?string $recordTitleAttribute = 'key';

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->is_admin;
    }

    public static function form(Schema $schema): Schema
    {
        return SettingForm::configure($schema);
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
            'index' => ListSettings::route('/'),
        ];
    }
}
