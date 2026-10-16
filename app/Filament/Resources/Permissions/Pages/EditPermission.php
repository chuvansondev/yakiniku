<?php

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Permissions\PermissionResource;
use App\Filament\Resources\Pages\EditRecord;
use App\Services\PermissionService;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(PermissionService::class)->update($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
