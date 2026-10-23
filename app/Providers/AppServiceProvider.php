<?php

namespace App\Providers;

use App\Models\User;
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
use App\Observers\PublicDataCacheObserver;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\Field;
use Filament\Tables\Columns\Column;
use Filament\Schemas\Components\Section;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $repositories = [
            \App\Repositories\Contracts\AdminResourceRepositoryContract::class => \App\Repositories\AdminResourceRepository::class,
            \App\Repositories\Contracts\SettingRepositoryContract::class => \App\Repositories\SettingRepository::class,
            \App\Repositories\Contracts\BookingRepositoryContract::class => \App\Repositories\BookingRepository::class,
            \App\Repositories\Contracts\LeadRepositoryContract::class => \App\Repositories\LeadRepository::class,
            \App\Repositories\Contracts\BannerRepositoryContract::class => \App\Repositories\BannerRepository::class,
            \App\Repositories\Contracts\BookingStatusHistoryRepositoryContract::class => \App\Repositories\BookingStatusHistoryRepository::class,
            \App\Repositories\Contracts\ComboRepositoryContract::class => \App\Repositories\ComboRepository::class,
            \App\Repositories\Contracts\ComboItemRepositoryContract::class => \App\Repositories\ComboItemRepository::class,
            \App\Repositories\Contracts\CustomerRepositoryContract::class => \App\Repositories\CustomerRepository::class,
            \App\Repositories\Contracts\KidItemRepositoryContract::class => \App\Repositories\KidItemRepository::class,
            \App\Repositories\Contracts\MenuCategoryRepositoryContract::class => \App\Repositories\MenuCategoryRepository::class,
            \App\Repositories\Contracts\MenuItemRepositoryContract::class => \App\Repositories\MenuItemRepository::class,
            \App\Repositories\Contracts\PermissionRepositoryContract::class => \App\Repositories\PermissionRepository::class,
            \App\Repositories\Contracts\PromotionRepositoryContract::class => \App\Repositories\PromotionRepository::class,
            \App\Repositories\Contracts\RecipeRepositoryContract::class => \App\Repositories\RecipeRepository::class,
            \App\Repositories\Contracts\RestaurantRepositoryContract::class => \App\Repositories\RestaurantRepository::class,
            \App\Repositories\Contracts\RoleRepositoryContract::class => \App\Repositories\RoleRepository::class,
            \App\Repositories\Contracts\TipRepositoryContract::class => \App\Repositories\TipRepository::class,
            \App\Repositories\Contracts\UserRepositoryContract::class => \App\Repositories\UserRepository::class,
        ];

        foreach ($repositories as $contract => $repository) {
            $this->app->bind($contract, $repository);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([Banner::class, Combo::class, ComboItem::class, KidItem::class, MenuCategory::class, MenuItem::class, Promotion::class, Recipe::class, Restaurant::class, Setting::class, Tip::class] as $model) {
            $model::observe(PublicDataCacheObserver::class);
        }

        Gate::policy(User::class, UserPolicy::class);
        Field::configureUsing(fn (Field $field) => $field->translateLabel());
        Column::configureUsing(fn (Column $column) => $column->translateLabel());
        Section::configureUsing(fn (Section $section) => $section->translateLabel());
    }
}
