<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KidsFoodCategory;
use App\Enums\KidsItemType;
use App\Http\Controllers\Controller;
use App\Models\KidItem;
use App\Services\AdminResourceService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\KidsItemRequest;
use Illuminate\View\View;

class KidsItemController extends Controller
{
    private const TYPE_LABELS = [
        KidsItemType::Food->value => 'Đồ ăn',
        KidsItemType::Utensil->value => 'Dụng cụ',
    ];

    private const FOOD_CATEGORY_LABELS = [
        KidsFoodCategory::Meat->value => 'Thịt',
        KidsFoodCategory::SideDish->value => 'Món ăn kèm',
        KidsFoodCategory::Vegetable->value => 'Rau củ',
        KidsFoodCategory::Soup->value => 'Súp',
        KidsFoodCategory::RiceNoodles->value => 'Cơm và mì',
        KidsFoodCategory::Dessert->value => 'Tráng miệng',
    ];

    public function index(\Illuminate\Http\Request $request, AdminResourceService $resources): View
    {
        $items = $resources->paginate(KidItem::class, $request, orderBy: [['type', 'asc'], ['sort_order', 'asc'], ['name', 'asc']]);

        return view('admin.kids-items.index', [
            'items' => $items,
            'typeLabels' => self::TYPE_LABELS,
            'foodCategoryLabels' => self::FOOD_CATEGORY_LABELS,
        ]);
    }

    public function create(): View
    {
        return view('admin.kids-items.create', [
            'typeLabels' => self::TYPE_LABELS,
            'foodCategoryLabels' => self::FOOD_CATEGORY_LABELS,
        ]);
    }

    public function store(KidsItemRequest $request, AdminResourceService $resources): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = $resources->slug(($validated['slug'] ?? null) ?: $validated['name']);
        $validated['image'] = $resources->storeImage($request->file('image'), 'kids/items');
        $validated['food_category'] = $validated['type'] === KidsItemType::Food->value
            ? ($validated['food_category'] ?? null)
            : null;
        $validated['sort_order'] = $validated['sort_order'] ?? null;
        $validated['status'] = $request->boolean('status');

        $resources->create(KidItem::class, $validated);

        return redirect()
            ->route('admin.kids-items.index')
            ->with('success', 'Thêm nội dung trẻ em thành công.');
    }

    public function edit(KidItem $kidItem): View
    {
        return view('admin.kids-items.edit', [
            'kidItem' => $kidItem,
            'typeLabels' => self::TYPE_LABELS,
            'foodCategoryLabels' => self::FOOD_CATEGORY_LABELS,
        ]);
    }

    public function update(KidsItemRequest $request, KidItem $kidItem, AdminResourceService $resources): RedirectResponse
    {
        $validated = $request->validated();
        $oldImage = null;

        $validated['slug'] = $resources->slug(($validated['slug'] ?? null) ?: $validated['name']);

        if ($request->hasFile('image')) {
            $oldImage = $kidItem->image;
            $validated['image'] = $resources->replaceImage($request->file('image'), false, $kidItem->image, 'kids/items');
        } elseif ($request->boolean('remove_image')) {
            $oldImage = $kidItem->image;
            $validated['image'] = $resources->replaceImage(null, true, $kidItem->image, 'kids/items');
        } else {
            unset($validated['image']);
        }

        unset($validated['remove_image']);
        $validated['food_category'] = $validated['type'] === KidsItemType::Food->value
            ? ($validated['food_category'] ?? null)
            : null;
        $validated['sort_order'] = $validated['sort_order'] ?? null;
        $validated['status'] = $request->boolean('status');

        $resources->update($kidItem, $validated);
        $resources->deleteImage($oldImage);

        return redirect()
            ->route('admin.kids-items.index')
            ->with('success', 'Cập nhật nội dung trẻ em thành công.');
    }

    public function destroy(KidItem $kidItem, AdminResourceService $resources): RedirectResponse
    {
        $resources->delete($kidItem);

        return redirect()
            ->route('admin.kids-items.index')
            ->with('success', 'Xóa nội dung trẻ em thành công.');
    }
}
