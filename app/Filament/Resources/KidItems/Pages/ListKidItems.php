<?php

namespace App\Filament\Resources\KidItems\Pages;

use App\Filament\Resources\KidItems\KidItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKidItems extends ListRecords
{
    protected static string $resource = KidItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
