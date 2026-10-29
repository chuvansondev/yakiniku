<?php

namespace Tests\Feature;

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_bulk_delete_customers_but_not_admin_accounts(): void
    {
        $this->withoutVite();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $admin = User::factory()->create();
        $otherAdmin = User::factory()->create();
        $customers = User::factory()->customer()->count(2)->create();
        $this->actingAs($admin);

        Livewire::test(ListUsers::class)
            ->assertTableBulkActionExists('delete')
            ->callTableBulkAction('delete', $customers->concat([$admin, $otherAdmin]));

        foreach ($customers as $customer) {
            $this->assertModelMissing($customer);
        }
        $this->assertModelExists($admin);
        $this->assertModelExists($otherAdmin);
    }

    public function test_customer_cannot_access_user_management_or_bulk_delete(): void
    {
        $customer = User::factory()->customer()->create();
        $this->actingAs($customer);

        $this->get('/admin/users')->assertForbidden();
        $this->assertFalse($customer->can('deleteAny', User::class));
    }
}
