<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Contracts\Support\Htmlable;

trait TranslatesResourceNavigation
{
    public static function getModelLabel(): string
    {
        return __('admin.model.'.strtolower(class_basename(static::getModel())));
    }

    public static function getPluralModelLabel(): string
    {
        return (string) static::getNavigationLabel();
    }

    public static function hasTitleCaseModelLabel(): bool
    {
        return app()->getLocale() === 'en';
    }

    public static function getNavigationLabel(): string|Htmlable
    {
        $label = match (static::getModel()) {
            \App\Models\Banner::class => 'admin.nav.banner',
            \App\Models\Booking::class => 'admin.nav.booking',
            \App\Models\Combo::class => 'admin.nav.combo',
            \App\Models\ComboItem::class => 'admin.nav.combo_item',
            \App\Models\Customer::class => 'admin.nav.customer',
            \App\Models\KidItem::class => 'admin.nav.kid_item',
            \App\Models\Lead::class => 'admin.nav.lead',
            \App\Models\MenuCategory::class => 'admin.nav.menu_category',
            \App\Models\MenuItem::class => 'admin.nav.menu_item',
            \App\Models\Permission::class => 'admin.nav.permission',
            \App\Models\Promotion::class => 'admin.nav.promotion',
            \App\Models\Recipe::class => 'admin.nav.recipe',
            \App\Models\Restaurant::class => 'admin.nav.restaurant',
            \App\Models\Role::class => 'admin.nav.role',
            \App\Models\Setting::class => 'admin.nav.setting',
            \App\Models\Tip::class => 'admin.nav.tip',
            \App\Models\User::class => 'admin.nav.user',
            default => parent::getNavigationLabel(),
        };

        return is_string($label) ? __($label) : $label;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        $group = match (static::getModel()) {
            \App\Models\Booking::class, \App\Models\Customer::class, \App\Models\Lead::class,
            \App\Models\Restaurant::class, \App\Models\User::class => 'admin.nav.group.booking_customers',
            \App\Models\Combo::class, \App\Models\ComboItem::class, \App\Models\KidItem::class,
            \App\Models\MenuCategory::class, \App\Models\MenuItem::class => 'admin.nav.group.menu',
            \App\Models\Banner::class, \App\Models\Promotion::class, \App\Models\Recipe::class,
            \App\Models\Tip::class => 'admin.nav.group.content',
            \App\Models\Permission::class, \App\Models\Role::class, \App\Models\Setting::class => 'admin.nav.group.system',
            default => parent::getNavigationGroup(),
        };

        return is_string($group) ? __($group) : $group;
    }
}
