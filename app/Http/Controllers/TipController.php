<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Services\PublicDataCache;

class TipController extends Controller
{
    public function index(): View
    {
        $tips = clone PublicDataCache::remember(PublicDataCache::TIPS, 'published', fn () => Tip::query()
            ->where('status', true)
            ->where(fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByRaw('published_at IS NULL')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get());

        $tips->each(fn (Tip $tip) => $tip->setAttribute('page_image_url', $this->imageUrl($tip->image)));

        return view('frontend.tips.index', compact('tips'));
    }

    private function imageUrl(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->temporaryUrl($path, now()->addDay());
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
