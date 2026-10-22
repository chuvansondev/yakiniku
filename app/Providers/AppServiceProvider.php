<?php

namespace App\Providers;

use App\Models\User;
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
            \App\Contracts\Repositories\SettingRepositoryInterface::class => \App\Repositories\SettingRepository::class,
            \App\Contracts\Repositories\BookingRepositoryInterface::class => \App\Repositories\BookingRepository::class,
            \App\Contracts\Repositories\LeadRepositoryInterface::class => \App\Repositories\LeadRepository::class,
            \App\Repositories\Contracts\BannerRepositoryContract::class => \App\Repositories\BannerRepository::class,
            \App\Repositories\Contracts\BookingRepositoryContract::class => \App\Repositories\BookingRepository::class,
            \App\Repositories\Contracts\BookingStatusHistoryRepositoryContract::class => \App\Repositories\BookingStatusHistoryRepository::class,
            \App\Repositories\Contracts\ComboRepositoryContract::class => \App\Repositories\ComboRepository::class,
            \App\Repositories\Contracts\ComboItemRepositoryContract::class => \App\Repositories\ComboItemRepository::class,
            \App\Repositories\Contracts\CustomerRepositoryContract::class => \App\Repositories\CustomerRepository::class,
            \App\Repositories\Contracts\KidItemRepositoryContract::class => \App\Repositories\KidItemRepository::class,
            \App\Repositories\Contracts\LeadRepositoryContract::class => \App\Repositories\LeadRepository::class,
            \App\Repositories\Contracts\MenuCategoryRepositoryContract::class => \App\Repositories\MenuCategoryRepository::class,
            \App\Repositories\Contracts\MenuItemRepositoryContract::class => \App\Repositories\MenuItemRepository::class,
            \App\Repositories\Contracts\PermissionRepositoryContract::class => \App\Repositories\PermissionRepository::class,
            \App\Repositories\Contracts\PromotionRepositoryContract::class => \App\Repositories\PromotionRepository::class,
            \App\Repositories\Contracts\RecipeRepositoryContract::class => \App\Repositories\RecipeRepository::class,
            \App\Repositories\Contracts\RestaurantRepositoryContract::class => \App\Repositories\RestaurantRepository::class,
            \App\Repositories\Contracts\RestaurantTableRepositoryContract::class => \App\Repositories\RestaurantTableRepository::class,
            \App\Repositories\Contracts\RoleRepositoryContract::class => \App\Repositories\RoleRepository::class,
            \App\Repositories\Contracts\SettingRepositoryContract::class => \App\Repositories\SettingRepository::class,
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
        Gate::policy(User::class, UserPolicy::class);
        Field::configureUsing(fn (Field $field) => $field->translateLabel());
        Column::configureUsing(fn (Column $column) => $column->translateLabel());
        Section::configureUsing(fn (Section $section) => $section->translateLabel());
    }
}
