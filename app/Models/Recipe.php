<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\TracksDeletion;
use App\Models\Concerns\GeneratesSlug;

class Recipe extends Model
{
    use SoftDeletes, TracksDeletion, GeneratesSlug;

    public const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'short_description',
        'short_description_en',
        'content',
        'content_en',
        'image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'published_at' => 'datetime',
    ];
}
