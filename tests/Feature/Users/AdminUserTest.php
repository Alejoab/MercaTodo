<?php

namespace Tests\Feature\Users;

use App\Domain\Users\Enums\PermissionEnum;
use App\Domain\Users\Enums\RoleEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\UserTestCase;

class AdminUserTest extends UserTestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_see_users(): void
    {
        $response = $this->get(route('admin.users'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->customer)->get(route('admin.users'));
        $response->assertStatus(403);
    }

    public function test_only_created_users_can_be_updated(): void
    {
        $response = $this->get(
            route('admin.user.show', $this->customer->id)
        );
        $response->assertStatus(200);

        $response = $this->get(
            route('admin.user.show', 0)
        );
        $response->assertStatus(404);
    }

    public function test_admin_can_update_user(): void
    {
        $response = $this->put(
            route('admin.user.update', $this->customer->id),
            [
                'role' => RoleEnum::CUSTOMER->value,
                'permissions' => [],
            ]
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.user.show', $this->customer->id));
    }

    public function test_admin_can_update_a_user_password(): void
    {
        $response = $this->put(
            route('admin.user.update.password', $this->customer->id),
            [
                'password' => 'Test_Password_0',
                'password_confirmation' => 'Test_Password_0',
            ]
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.user.show', $this->customer->id));
    }

    public function test_admin_can_disable_and_enable_a_user(): void
    {
        $response = $this->delete(
            route('admin.user.destroy', $this->customer->id)
        );

        $response->assertSessionHasNoErrors();
        $this->customer->refresh();
        $this->assertNotNull($this->customer->deleted_at);

        $response = $this->put(
            route('admin.user.restore', $this->customer->id)
        );

        $response->assertSessionHasNoErrors();
        $this->customer->refresh();
        $this->assertNull($this->customer->refresh()->deleted_at);
    }

    public function test_admin_can_force_delete_a_user(): void
    {
        $response = $this->delete(route('admin.user.force-delete', $this->customer->id), [
                'password' => 'password',
            ]
        );

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_the_admin_password_is_required_when_force_deleting_a_user(): void
    {
        $response = $this->delete(
            route('admin.user.force-delete', $this->customer->id)
        );
        $response->assertSessionHasErrors('password');
    }

    public function test_admin_can_update_the_role_of_the_user(): void
    {
        Role::create(['name' => RoleEnum::ADMIN->value]);
        Permission::create(['name' => PermissionEnum::UPDATE->value]);

        $this->put(route('admin.user.update', $this->customer->id), [
            'role' => RoleEnum::ADMIN->value,
            'permissions' => [PermissionEnum::UPDATE->value],
        ]);

        $this->customer->refresh();
        $this->assertContains(RoleEnum::ADMIN->value, $this->customer->getRoleNames());
        $this->assertContains(PermissionEnum::UPDATE->value, $this->customer->getAllPermissions()->pluck('name')->toArray());
    }
}
