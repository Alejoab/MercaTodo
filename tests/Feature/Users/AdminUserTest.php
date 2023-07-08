<?php

namespace Tests\Feature\Users;

use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Department;
use App\Domain\Users\Enums\PermissionEnum;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    public function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::create(['name' => RoleEnum::SUPER_ADMIN->value]);
        $roleCustomer = Role::create(['name' => RoleEnum::CUSTOMER->value]);

        Department::factory(1)->create();
        City::factory(1)->create();

        $this->user = User::factory()->create();
        $this->user->assignRole($roleCustomer);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);
    }

    public function test_only_admin_can_see_users(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->user)->get(route('admin.users'));
        $response->assertStatus(403);
    }

    public function test_only_created_users_can_be_updated(): void
    {
        $response = $this->actingAs($this->admin)->get(
            route('admin.user.show', $this->user->id)
        );
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(
            route('admin.user.show', 0)
        );
        $response->assertStatus(404);
    }

    public function test_admin_can_update_user(): void
    {
        $response = $this->actingAs($this->admin)->put(
            route('admin.user.update', $this->user->id),
            [
                'role' => RoleEnum::CUSTOMER->value,
                'permissions' => [],
            ]
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.user.show', $this->user->id));
    }

    public function test_admin_can_update_a_user_password(): void
    {
        $response = $this->actingAs($this->admin)->put(
            route('admin.user.update.password', $this->user->id),
            [
                'password' => 'Test_Password_0',
                'password_confirmation' => 'Test_Password_0',
            ]
        );

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.user.show', $this->user->id));
    }

    public function test_admin_can_disable_and_enable_a_user(): void
    {
        $response = $this->actingAs($this->admin)->delete(
            route('admin.user.destroy', $this->user->id)
        );
        $response->assertSessionHasNoErrors();
        $this->assertNotNull($this->user->refresh()->deleted_at);

        $response = $this->actingAs($this->admin)->put(
            route('admin.user.restore', $this->user->id)
        );
        $response->assertSessionHasNoErrors();
        $this->assertNull($this->user->refresh()->deleted_at);
    }

    public function test_admin_can_force_delete_a_user(): void
    {
        $response = $this->actingAs($this->admin)->delete(
            route('admin.user.force-delete', $this->user->id),
            [
                'password' => 'password',
            ]
        );
        $response->assertSessionHasNoErrors();
        $this->assertNull(User::find($this->user->id));
    }

    public function test_the_admin_password_is_required_when_force_deleting_a_user(): void
    {
        $response = $this->actingAs($this->admin)->delete(
            route('admin.user.force-delete', $this->user->id)
        );
        $response->assertSessionHasErrors('password');
    }

    public function test_admin_can_update_the_role_of_the_user(): void
    {
        Role::create(['name' => RoleEnum::ADMIN->value]);
        Permission::create(['name' => PermissionEnum::UPDATE->value]);

        $this->actingAs($this->admin)->put(route('admin.user.update', $this->user->id), [
            'role' => RoleEnum::ADMIN->value,
            'permissions' => [PermissionEnum::UPDATE->value],
        ]);

        $this->user->refresh();
        $this->assertContains(RoleEnum::ADMIN->value, $this->user->getRoleNames());
        $this->assertContains(PermissionEnum::UPDATE->value, $this->user->getAllPermissions()->pluck('name')->toArray());
    }
}
