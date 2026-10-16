<?php

namespace App\Filament\Resources\ComboItems\Pages;

use App\Filament\Resources\ComboItems\ComboItemResource;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\EditRecord;

class EditComboItem extends EditRecord
{
    protected static string $resource = ComboItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
