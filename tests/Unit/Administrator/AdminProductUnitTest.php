<?php

namespace Tests\Unit\Administrator;

use App\Models\City;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProductUnitTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $roleAdmin = Role::create(['name' => 'Administrator']);

        Department::factory(1)->create();
        City::factory(1)->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);
    }

    public function test_creation_product_form_has_expected_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.create'), [
            'code' => '00000001',
            'category_name' => '',
            'brand_name' => '$brand->name',
            'name' => '',
            'description' => '',
            'image_path' => 'a',
            'price' => -1,
            'stock' => -1,
        ]);

        $response->assertSessionHasErrors();
    }
}
