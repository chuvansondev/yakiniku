<?php

namespace App\Filament\Resources\Restaurants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RestaurantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->wrap()
                    ->searchable(),
                TextColumn::make('address')
                    ->label(app()->getLocale() === 'en' ? 'Address' : 'Địa chỉ')
                    ->wrap()
                    ->extraCellAttributes(['style' => 'min-width: 200px; max-width: 320px;'])
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('latitude')->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('google_map_url')
                    ->label(app()->getLocale() === 'en' ? 'Map' : 'Bản đồ')
                    ->formatStateUsing(fn (): string => app()->getLocale() === 'en' ? 'View map' : 'Xem bản đồ')
                    ->url(fn ($record): ?string => $record->google_map_url)
                    ->openUrlInNewTab()
                    ->searchable(),
                TextColumn::make('opening_time')->label(app()->getLocale() === 'en' ? 'Opens' : 'Giờ mở')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('closing_time')->label(app()->getLocale() === 'en' ? 'Closes' : 'Giờ đóng')
                    ->time('H:i')
                    ->sortable(),
                ImageColumn::make('image')->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
