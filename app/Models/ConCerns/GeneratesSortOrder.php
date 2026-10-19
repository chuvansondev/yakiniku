<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait GeneratesSortOrder
{
    protected static function bootGeneratesSortOrder(): void
    {
        static::saving(function (Model $record): void {
            if (filled($record->getAttribute('sort_order'))) {
                return;
            }

            $query = $record->newQueryWithoutScopes();

            if ($record->exists) {
                $query->where($record->getKeyName(), '!=', $record->getKey());
            }

            $record->setAttribute('sort_order', ((int) $query->max('sort_order')) + 1);
        });
    }
}
