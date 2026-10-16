<?php

namespace App\Filament\Resources\KidItems\Pages;

use App\Filament\Resources\KidItems\KidItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditKidItem extends EditRecord
{
    protected static string $resource = KidItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
