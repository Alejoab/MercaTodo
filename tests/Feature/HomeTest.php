<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Department;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;

    public function setUp(): void
    {
        parent::setUp();
        $roleAdmin = Role::create(['name' => RoleEnum::SUPER_ADMIN->value]);
        $roleCustomer = Role::create(['name' => RoleEnum::CUSTOMER->value]);

        Department::factory(1)->create();
        City::factory(1)->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($roleCustomer);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->customer)->get(route('home', [
            'search' => 'test',
            'category' => 1,
            'brand' => [1, 2],
        ]));
        $response->assertStatus(200);
    }
}
