<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\City;
use App\Models\Department;
use App\Models\User;
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
        $roleAdmin = Role::create(['name' => 'Administrator']);
        $roleCustomer = Role::create(['name' => 'Customer']);

        Department::factory(1)->create();
        City::factory(1)->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($roleCustomer);
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->customer)->get('/');
        $response->assertStatus(200);
    }
}
