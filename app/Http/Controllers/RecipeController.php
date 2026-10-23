<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Services\PublicDataCache;

class RecipeController extends Controller
{
    public function index(): View
    {
        $recipes = clone PublicDataCache::remember(PublicDataCache::RECIPES, 'published', fn () => Recipe::query()
            ->where('status', true)
            ->where(fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByRaw('published_at IS NULL')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get());

        $recipes->each(fn (Recipe $recipe) => $recipe->setAttribute('menu_image_url', $this->imageUrl($recipe->image)));

        return view('frontend.recipes.index', compact('recipes'));
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
