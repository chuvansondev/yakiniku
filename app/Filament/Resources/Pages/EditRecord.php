<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Concerns\UsesResourceRepository;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;
use Illuminate\Database\Eloquent\Model;

abstract class EditRecord extends FilamentEditRecord
{
    use UsesResourceRepository;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return $this->getRepository()->update($record, $data);
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }
}
