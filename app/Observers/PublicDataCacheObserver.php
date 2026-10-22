<?php

namespace App\Observers;

use App\Models\Banner;
use App\Models\Combo;
use App\Models\ComboItem;
use App\Models\KidItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use App\Models\Recipe;
use App\Models\Restaurant;
use App\Models\Setting;
use App\Models\Tip;
use App\Services\PublicDataCache;
use Illuminate\Database\Eloquent\Model;

class PublicDataCacheObserver
{
    public function saved(Model $model): void
    {
        $this->clearFor($model);
    }

    public function deleted(Model $model): void
    {
        $this->clearFor($model);
    }

    public function restored(Model $model): void
    {
        $this->clearFor($model);
    }

    private function clearFor(Model $model): void
    {
        match (true) {
            $model instanceof MenuCategory, $model instanceof MenuItem => $this->clearMenu(),
            $model instanceof KidItem => PublicDataCache::clear(PublicDataCache::KIDS_ITEMS),
            $model instanceof Combo, $model instanceof ComboItem => PublicDataCache::clear(PublicDataCache::COMBOS),
            $model instanceof Promotion => PublicDataCache::clear(PublicDataCache::PROMOTIONS),
            $model instanceof Banner => PublicDataCache::clear(PublicDataCache::BANNERS),
            $model instanceof Setting => PublicDataCache::clear(PublicDataCache::SETTINGS),
            $model instanceof Restaurant => PublicDataCache::clear(PublicDataCache::RESTAURANTS),
            $model instanceof Recipe => PublicDataCache::clear(PublicDataCache::RECIPES),
            $model instanceof Tip => PublicDataCache::clear(PublicDataCache::TIPS),
            default => null,
        };
    }

    private function clearMenu(): void
    {
        PublicDataCache::clear(PublicDataCache::MENU);

        // Combo retail totals are calculated from their active menu items.
        PublicDataCache::clear(PublicDataCache::COMBOS);
    }
}
