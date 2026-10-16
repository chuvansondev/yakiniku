<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Concerns\UsesResourceRepository;
use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;
use Illuminate\Database\Eloquent\Model;

abstract class CreateRecord extends FilamentCreateRecord
{
    use UsesResourceRepository;

    protected function handleRecordCreation(array $data): Model
    {
        if ($this->getParentRecord()) {
            return parent::handleRecordCreation($data);
        }

        return $this->getRepository()->create($data);
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
