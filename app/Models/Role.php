<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\GeneratesSlug;
use App\Models\Pivots\PermissionRole;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    use GeneratesSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (Role $role): void {
            $role->create_by ??= Auth::id();
            $role->update_by ??= Auth::id();
        });

        static::updating(function (Role $role): void {
            $role->update_by = Auth::id();
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
            ->using(PermissionRole::class)
            ->withPivot(['create_by', 'update_by'])
            ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
