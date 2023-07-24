<?php

namespace Tests\Unit\Customers;

use App\Domain\Customers\Actions\CreateCustomerAction;
use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Customer;
use App\Domain\Customers\Models\Department;
use App\Support\Exceptions\CustomException;
use Cassandra\Custom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UserTestCase;

class CustomerUnitTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Department::factory()->create([
            'name' => 'Cundinamarca',
        ]);

        City::factory()->create([
            'name' => 'Bogotá',
        ]);

        Customer::factory()->create([
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_get_city_and_department_from_the_customer(): void
    {
        $city_name = $this->admin->customer->city->name;
        $department_name = $this->admin->customer->city->department->name;

        $this->assertEquals('Bogotá', $city_name);
        $this->assertEquals('Cundinamarca', $department_name);
    }

    public function test_get_users_from_a_department(): void
    {
        $department = Department::query()->first();
        $users = $department->cities[0]->customers;

        $this->assertEquals(1, $users->count());
    }
}
