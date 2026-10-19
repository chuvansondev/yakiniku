<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $banners = Banner::query()->where('status', true)->orderBy('sort_order')->orderBy('id')->get();
        $banners->each(function (Banner $banner): void {
            $path = $banner->image;
            $imageUrl = null;

            if (filled($path)) {
                if (Str::startsWith($path, ['http://', 'https://', '/'])) {
                    $imageUrl = $path;
                } elseif (Storage::disk('public')->exists($path)) {
                    $imageUrl = Storage::disk('public')->url($path);
                } elseif (Storage::disk('local')->exists($path)) {
                    $imageUrl = Storage::disk('local')->temporaryUrl($path, now()->addDay());
                } else {
                    $imageUrl = asset('storage/'.ltrim($path, '/'));
                }
            }

            $banner->setAttribute('homepage_image_url', $imageUrl);
        });
        $categories = MenuCategory::query()
            ->orderBy('sort_order')->orderBy('id')->limit(6)->get();
        $categories->each(function (MenuCategory $category): void {
            $path = $category->image;
            $imageUrl = null;

            if (filled($path)) {
                if (Str::startsWith($path, ['http://', 'https://', '/'])) {
                    $imageUrl = $path;
                } elseif (Storage::disk('public')->exists($path)) {
                    $imageUrl = Storage::disk('public')->url($path);
                } elseif (Storage::disk('local')->exists($path)) {
                    $imageUrl = Storage::disk('local')->temporaryUrl($path, now()->addDay());
                } else {
                    $imageUrl = asset('storage/'.ltrim($path, '/'));
                }
            }

            $category->setAttribute('homepage_image_url', $imageUrl);
        });
        $restaurants = Restaurant::query()->where('status', true)->orderBy('name')->get();

        return view('home.index', compact('banners', 'categories', 'restaurants'));
    }
}
