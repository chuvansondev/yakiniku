<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Combo;
use App\Models\Promotion;
use App\Models\KidItem;
use App\Enums\KidsFoodCategory;
use App\Enums\KidsItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __invoke(): View
    {
        return $this->renderMenu(false);
    }

    public function mustTry(): View
    {
        return $this->renderMenu(true);
    }

    public function combos(): View
    {
        $combos = Combo::query()
            ->where('status', true)
            ->where(fn ($query) => $query->whereNull('start_date')->orWhereDate('start_date', '<=', today()))
            ->where(fn ($query) => $query->whereNull('end_date')->orWhereDate('end_date', '>=', today()))
            ->with(['menuItems' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $combos->each(fn (Combo $combo) => $combo->setAttribute('menu_image_url', $this->imageUrl($combo->image)));

        return view('menu.combos', compact('combos'));
    }

    public function promotions(): View
    {
        $promotions = Promotion::query()
            ->where('status', true)
            ->where(fn ($query) => $query->whereNull('start_date')->orWhereDate('start_date', '<=', today()))
            ->where(fn ($query) => $query->whereNull('end_date')->orWhereDate('end_date', '>=', today()))
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $promotions->each(fn (Promotion $promotion) => $promotion->setAttribute('menu_image_url', $this->imageUrl($promotion->image)));

        return view('menu.promotions', compact('promotions'));
    }

    public function forKids(Request $request): View
    {
        $types = collect(KidsItemType::cases())->mapWithKeys(fn (KidsItemType $type) => [$type->value => $type->value]);
        $categories = collect(KidsFoodCategory::cases())->mapWithKeys(fn (KidsFoodCategory $category) => [$category->value => $category->value]);
        $selectedType = $request->query('type');
        $selectedCategory = $request->query('category');

        if (! is_string($selectedType) || ! $types->has($selectedType)) {
            $selectedType = '';
        }
        if (! is_string($selectedCategory) || ! $categories->has($selectedCategory)) {
            $selectedCategory = '';
        }

        $items = KidItem::query()
            ->where('status', true)
            ->when($selectedType !== '', fn ($query) => $query->where('type', $selectedType))
            ->when($selectedCategory !== '', fn ($query) => $query->where('food_category', $selectedCategory))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $items->each(fn (KidItem $item) => $item->setAttribute('menu_image_url', $this->imageUrl($item->image)));

        $typeLabels = [
            KidsItemType::Food->value => $request->getLocale() === 'en' ? 'Food' : 'Món ăn',
            KidsItemType::Utensil->value => $request->getLocale() === 'en' ? 'Utensils' : 'Dụng cụ',
        ];
        $categoryLabels = [
            KidsFoodCategory::Meat->value => $request->getLocale() === 'en' ? 'Meat' : 'Thịt',
            KidsFoodCategory::SideDish->value => $request->getLocale() === 'en' ? 'Side dishes' : 'Món ăn kèm',
            KidsFoodCategory::Vegetable->value => $request->getLocale() === 'en' ? 'Vegetables' : 'Rau củ',
            KidsFoodCategory::Soup->value => $request->getLocale() === 'en' ? 'Soup' : 'Súp',
            KidsFoodCategory::RiceNoodles->value => $request->getLocale() === 'en' ? 'Rice & noodles' : 'Cơm và mì',
            KidsFoodCategory::Dessert->value => $request->getLocale() === 'en' ? 'Desserts' : 'Tráng miệng',
        ];

        return view('menu.for-kid', compact('items', 'typeLabels', 'categoryLabels', 'selectedType', 'selectedCategory'));
    }

    private function renderMenu(bool $mustTryOnly): View
    {
        $itemsQuery = fn ($query) => $query
            ->when($mustTryOnly, fn ($query) => $query->where('is_must_try', true))
            ->orderBy('sort_order')
            ->orderBy('id');

        $categories = MenuCategory::query()
            ->where('status', true)
            ->whereHas('menuItems', $itemsQuery)
            ->with(['menuItems' => $itemsQuery])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $categories->each(function (MenuCategory $category): void {
            $category->setAttribute('menu_image_url', $this->imageUrl($category->image));
        });

        return view('menu.index', compact('categories', 'mustTryOnly'));
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
