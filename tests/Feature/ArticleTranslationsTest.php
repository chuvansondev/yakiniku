<?php

namespace Tests\Feature;

use App\Filament\Resources\Recipes\Pages\CreateRecipe;
use App\Filament\Resources\Recipes\Pages\EditRecipe;
use App\Filament\Resources\Tips\Pages\CreateTip;
use App\Filament\Resources\Tips\Pages\EditTip;
use App\Models\Recipe;
use App\Models\Tip;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleTranslationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_edit_english_articles_and_public_pages_use_them(): void
    {
        $this->withoutVite();
        $this->actingAs(User::factory()->create());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        foreach ([[Recipe::class, CreateRecipe::class, EditRecipe::class, 'secret.recipes'], [Tip::class, CreateTip::class, EditTip::class, 'secret.tips']] as [$model, $createPage, $editPage, $route]) {
            Livewire::test($createPage)->fillForm([
                'title' => 'Vietnamese title', 'content' => 'Vietnamese content',
                'title_en' => 'English article title', 'short_description_en' => 'English summary',
                'content_en' => 'English article content', 'status' => true,
            ])->call('create')->assertHasNoFormErrors();

            $article = $model::query()->sole();
            $this->assertSame('English article title', $article->title_en);
            Livewire::test($editPage, ['record' => $article->getRouteKey()])
                ->assertFormSet(['title_en' => 'English article title', 'content_en' => 'English article content'])
                ->fillForm(['content_en' => 'Updated English content'])->call('save')->assertHasNoFormErrors();

            $this->withSession(['locale' => 'en'])->get(route($route))->assertOk()
                ->assertSee('English article title')->assertSee('English summary')->assertSee('Updated English content')
                ->assertDontSee('Vietnamese content');
            $this->withSession(['locale' => 'vi'])->get(route($route))->assertOk()
                ->assertSee('Vietnamese title')->assertSee('Vietnamese content')->assertDontSee('Updated English content');
        }
    }

    public function test_articles_without_english_content_fall_back_to_original_content(): void
    {
        $this->withoutVite();
        foreach ([[Recipe::class, 'secret.recipes'], [Tip::class, 'secret.tips']] as [$model, $route]) {
            $model::create(['title' => 'Original title', 'content' => 'Original content', 'status' => true]);
            $this->withSession(['locale' => 'en'])->get(route($route))->assertOk()
                ->assertSee('Original title')->assertSee('Original content');
        }
    }
}
