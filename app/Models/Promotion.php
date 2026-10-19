<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\TracksDeletion;
use App\Models\Concerns\GeneratesSlug;

class Promotion extends Model
{
    use SoftDeletes, TracksDeletion, GeneratesSlug;

    public const DELETED_AT = 'delete_at';

    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'short_description',
        'short_description_en',
        'description',
        'description_en',
        'image',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];
}
