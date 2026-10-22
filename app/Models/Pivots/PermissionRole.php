<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class PermissionRole extends Pivot
{
    protected $table = 'permission_role';

    public $incrementing = false;

    protected static function booted(): void
    {
        static::creating(function (PermissionRole $pivot): void {
            $pivot->create_by ??= Auth::id();
            $pivot->update_by ??= Auth::id();
        });

        static::updating(function (PermissionRole $pivot): void {
            $pivot->update_by = Auth::id();
        });
    }
}
