<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Concerns\GeneratesSlug;
use App\Models\Pivots\PermissionRole;
use Illuminate\Support\Facades\Auth;

class Permission extends Model
{
    use GeneratesSlug;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (Permission $permission): void {
            $permission->create_by ??= Auth::id();
            $permission->update_by ??= Auth::id();
        });

        static::updating(function (Permission $permission): void {
            $permission->update_by = Auth::id();
        });
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id')
            ->using(PermissionRole::class)
            ->withPivot(['create_by', 'update_by'])
            ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
