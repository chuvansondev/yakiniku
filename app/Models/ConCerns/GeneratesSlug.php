<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait GeneratesSlug
{
    protected static function bootGeneratesSlug(): void
    {
        static::saving(function (Model $record): void {
            if (filled($record->getAttribute('slug'))) {
                return;
            }

            $source = $record->getAttribute('name') ?: $record->getAttribute('title');
            $baseSlug = Str::slug($source ?: class_basename($record)) ?: 'record';
            $slug = $baseSlug;
            $suffix = 2;

            while ($record->newQueryWithoutScopes()
                ->where('slug', $slug)
                ->when($record->exists, fn ($query) => $query->where($record->getKeyName(), '!=', $record->getKey()))
                ->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            $record->setAttribute('slug', $slug);
        });
    }
}
