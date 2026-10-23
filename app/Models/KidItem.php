<?php

namespace App\Models;

use Database\Factories\KidItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\TracksDeletion;
use App\Models\Concerns\GeneratesSlug;
use App\Models\Concerns\GeneratesSortOrder;

class KidItem extends Model
{
    /** @use HasFactory<KidItemFactory> */
    use HasFactory;
    use SoftDeletes, TracksDeletion;
    use GeneratesSlug, GeneratesSortOrder;

    public const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'type',
        'food_category',
        'description',
        'description_en',
        'image',
        'price',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];
}
