<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Users\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['is_admin'] = false;
        $data['is_locked'] = (bool) ($data['is_locked'] ?? false);

        return $data;
    }
}
