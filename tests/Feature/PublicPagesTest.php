<?php

namespace Tests\Feature;

use App\Models\KidItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_with_an_empty_database(): void
    {
        $this->withoutVite();

        foreach (['home', 'about', 'menu.our-menu', 'menu.must-try', 'menu.combos', 'menu.promotions', 'menu.for-kids', 'secret.recipes', 'secret.tips'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_kids_menu_displays_factory_items(): void
    {
        $this->withoutVite();
        $item = KidItem::factory()->create(['name' => 'Kids menu test item']);

        $this->get(route('menu.for-kids'))->assertOk()->assertSee($item->name);
    }

    public function test_all_repository_contracts_resolve_to_implementations(): void
    {
        foreach (glob(app_path('Repositories/Contracts/*Contract.php')) as $file) {
            $name = basename($file, '.php');
            if ($name === 'RepositoryContract') {
                continue;
            }

            $contract = 'App\\Repositories\\Contracts\\'.$name;
            $this->assertInstanceOf($contract, $this->app->make($contract));
        }
    }

    public function test_filament_resources_can_resolve_their_model_repositories(): void
    {
        foreach (glob(app_path('Filament/Resources/*/*Resource.php')) as $file) {
            $resource = 'App\\Filament\\Resources\\'.basename(dirname($file)).'\\'.basename($file, '.php');
            $model = $resource::getModel();
            $repository = $this->app->make('App\\Repositories\\'.class_basename($model).'Repository');

            $this->assertInstanceOf(\App\Repositories\Contracts\RepositoryContract::class, $repository);
            $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $repository->all());
        }
    }
}
