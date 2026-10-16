<?php

namespace App\Filament\Resources\ComboItems\Pages;

use App\Filament\Resources\ComboItems\ComboItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComboItems extends ListRecords
{
    protected static string $resource = ComboItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
