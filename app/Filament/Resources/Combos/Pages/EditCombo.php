<?php

namespace App\Filament\Resources\Combos\Pages;

use App\Filament\Resources\Combos\ComboResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Resources\Pages\EditRecord;

class EditCombo extends EditRecord
{
    protected static string $resource = ComboResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
