<?php

namespace Tests;

use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Department;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use Spatie\Permission\Models\Role;

class UserTestCase extends TestCase
{
    protected User $admin;
    protected User $customer;

    public function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::create(['name' => RoleEnum::SUPER_ADMIN->value]);
        $roleCustomer = Role::create(['name' => RoleEnum::CUSTOMER->value]);

        Department::factory()->create();
        City::factory()->create();

        /**
         * @var User $admin
         */
        $admin = User::factory()->create();
        $this->admin = $admin;
        $this->admin->assignRole($roleAdmin);

        /**
         * @var User $customer
         */
        $customer = User::factory()->create();
        $this->customer = $customer;
        $this->customer->assignRole($roleCustomer);

        $this->actingAs($this->admin);
    }
}
