<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Users\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['is_admin']);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        if ($this->record->is(auth()->user())) {
            $data['is_locked'] = false;
        }

        return $data;
    }
}
