<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $restaurants = Restaurant::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $restaurants->each(function (Restaurant $restaurant): void {
            $restaurant->setAttribute('page_image_url', $this->imageUrl($restaurant->image));
            $mapQuery = filled($restaurant->latitude) && filled($restaurant->longitude)
                ? $restaurant->latitude.','.$restaurant->longitude
                : $restaurant->address;
            $restaurant->setAttribute('page_map_url', filled($restaurant->google_map_url)
                ? $restaurant->google_map_url
                : 'https://www.google.com/maps?q='.urlencode($mapQuery));
            $restaurant->setAttribute('page_map_embed_url', 'https://www.google.com/maps?q='.urlencode($mapQuery).'&output=embed');
        });

        return view('about.index', compact('restaurants'));
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
