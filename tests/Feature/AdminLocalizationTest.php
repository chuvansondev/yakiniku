<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_admin_resource_forms_render_in_both_languages(): void
    {
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        foreach (['en', 'vi'] as $locale) {
            foreach (glob(app_path('Filament/Resources/*/*Resource.php')) as $file) {
                $resource = 'App\\Filament\\Resources\\'.basename(dirname($file)).'\\'.basename($file, '.php');
                if (!array_key_exists('create', $resource::getPages())) {
                    continue;
                }

                $this->withSession(['locale' => $locale])->get($resource::getUrl('create'))->assertOk();
                $this->assertNotSame('admin.model.'.strtolower(class_basename($resource::getModel())), $resource::getModelLabel());
            }
        }
    }

    public function test_admin_dashboard_and_settings_follow_selected_language(): void
    {
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $this->withSession(['locale' => 'en'])->get('/admin')
            ->assertOk()->assertSee('Booking trends')->assertDontSee('Xu hướng đặt bàn');
        $this->get('/admin/settings')->assertOk()
            ->assertSee('Save changes')->assertSee('Header brand name (Vietnamese)')
            ->assertDontSee('Thêm nhóm nhãn con');

        $this->post(route('locale.update'), ['locale' => 'vi'])->assertRedirect();
        $this->get('/admin')->assertOk()->assertSee('Xu hướng đặt bàn')->assertDontSee('Booking trends');
        $this->get('/admin/settings')->assertOk()->assertSee('Lưu thay đổi')->assertSee('Thêm nhóm nhãn con');
    }

    public function test_admin_resource_headings_and_form_labels_are_translated(): void
    {
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $this->withSession(['locale' => 'en'])->get('/admin/recipes/create')->assertOk()
            ->assertSee('Recipe')->assertSee('Short description')->assertSee('Content')
            ->assertDontSee('Để trống để tự tạo từ tiêu đề.');
        $this->withSession(['locale' => 'vi'])->get('/admin/recipes/create')->assertOk()
            ->assertSee('Công thức')->assertSee('Mô tả ngắn')->assertSee('Nội dung');
        $this->withSession(['locale' => 'vi'])->get('/admin/banners/create')->assertOk()
            ->assertSee('Tiêu đề')->assertSee('Hình ảnh')->assertSee('Để trống để tự tăng thứ tự.');
    }
}
