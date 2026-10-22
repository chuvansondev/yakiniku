<?php

namespace App\Filament\Resources\Concerns;

use App\Models\Banner;
use App\Models\Booking;
use App\Models\Combo;
use App\Models\ComboItem;
use App\Models\Customer;
use App\Models\KidItem;
use App\Models\Lead;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use App\Models\Recipe;
use App\Models\Restaurant;
use App\Models\Setting;
use App\Models\Tip;
use Illuminate\Database\Eloquent\Model;

trait UsesManagePermission
{
    use TranslatesResourceNavigation;

    private static function managePermission(): ?string
    {
        return match (static::getModel()) {
            Banner::class => 'menu.banners.manage',
            Booking::class => 'menu.bookings.manage',
            Combo::class, ComboItem::class => 'menu.combos.manage',
            Customer::class => 'menu.customers.manage',
            KidItem::class => 'kids-items.manage',
            Lead::class => 'menu.leads.manage',
            MenuCategory::class => 'menu.categories.manage',
            MenuItem::class => 'menu.items.manage',
            Promotion::class => 'menu.promotions.manage',
            Recipe::class => 'menu.recipes.manage',
            Restaurant::class => 'menu.restaurants.manage',
            Setting::class => 'menu.settings.manage',
            Tip::class => 'menu.tips.manage',
            default => null,
        };
    }

    private static function userCanManage(): bool
    {
        $permission = static::managePermission();

        return $permission !== null && (bool) auth()->user()?->hasPermission($permission);
    }

    public static function canViewAny(): bool
    {
        return static::userCanManage();
    }

    public static function canCreate(): bool
    {
        return static::userCanManage();
    }

    public static function canEdit(Model $record): bool
    {
        return static::userCanManage();
    }

    public static function canDelete(Model $record): bool
    {
        return static::userCanManage();
    }

    public static function canDeleteAny(): bool
    {
        return static::userCanManage();
    }
}
