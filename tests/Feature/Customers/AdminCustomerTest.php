<?php

namespace Tests\Feature\Customers;

use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Customer;
use App\Domain\Customers\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UserTestCase;

class AdminCustomerTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Department::factory()->create();
        City::factory()->create();

        Customer::factory()->create([
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_only_admin_can_see_customers(): void
    {
        $this->get(route('admin.customers'))->assertOk();
        $this->actingAs($this->customer)->get(route('admin.customers'))->assertForbidden();
    }

    public function test_admin_can_see_a_customer(): void
    {
        $this->get(route('admin.customer.show', $this->admin->id))->assertOk();
    }

    public function test_admin_can_update_a_customer(): void
    {
        $response = $this->putJson(route('admin.customer.update', $this->admin->id), [
            'name' => 'New Name',
            'surname' => 'New Last Name',
            'email' => $this->admin->email,
            'document_type' => 'CC',
            'document' => '123456789',
            'phone' => '1234567891',
            'address' => 'New Address',
            'city_id' => $this->admin->customer->city_id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('customers', [
            'name' => 'New Name',
            'surname' => 'New Last Name',
            'document_type' => 'CC',
            'document' => '123456789',
            'phone' => '1234567891',
            'address' => 'New Address',
        ]);
    }

    public function test_not_found_customer(): void
    {
        $this->get(route('admin.customer.show', -1))->assertNotFound();
    }

    public function test_users_can_see_departments_and_cities(): void
    {
        $this->get(route('departments'))->assertOk();
        $this->get(route('cities', 1))->assertOk();
    }
}
