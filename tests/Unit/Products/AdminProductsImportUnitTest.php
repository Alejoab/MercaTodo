<?php

namespace Tests\Unit\Administrator;

use App\Console\Jobs\ProductsImport;
use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Department;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Models\JobsByUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProductsImportUnitTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private JobsByUser $import;

    public function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::create(['name' => RoleEnum::SUPER_ADMIN->value]);
        Department::factory(1)->create();
        City::factory(1)->create();
        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);

        $this->import = JobsByUser::create([
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::IMPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_import_products_with_queue(): void
    {
        Excel::fake();

        Excel::queueImport(new ProductsImport($this->import), 'test_1.xlsx', 'tests');

        Excel::assertQueued('test_1.xlsx', 'tests');
    }

    public function test_import_change_value_in_database(): void
    {
        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->import->id,
            'status' => JobsByUserStatus::PENDING,
        ]);

        Excel::import(new ProductsImport($this->import), 'test_1.xlsx', 'tests');

        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->import->id,
            'status' => JobsByUserStatus::COMPLETED,
        ]);
    }

    public function test_import_process_when_create_data(): void
    {
        Excel::import(new ProductsImport($this->import), 'test_1.xlsx', 'tests');

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseHas('products', [
            'code' => 111111,
            'name' => 'Test 1',
            'description' => 'Test 1',
            'price' => 10,
            'stock' => 10,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'name' => 'Category 1',
        ]);

        $this->assertDatabaseCount('brands', 1);
        $this->assertDatabaseHas('brands', [
            'name' => 'Brand 1',
        ]);
    }

    public function test_import_process_when_update_data(): void
    {
        Excel::import(new ProductsImport($this->import), 'test_1.xlsx', 'tests');
        Excel::import(new ProductsImport($this->import), 'test_2.xlsx', 'tests');

        $this->assertDatabaseCount('products', 2);
        $this->assertDatabaseHas('products', [
            'code' => 111111,
            'name' => 'Test 3',
            'description' => 'Test 3',
            'price' => 10,
            'stock' => 10,
        ]);
        $this->assertDatabaseHas('products', [
            'code' => 222222,
            'name' => 'Test 2',
            'description' => 'Test 2',
            'price' => 10,
            'stock' => 10,
        ]);

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'name' => 'Category 1',
        ]);

        $this->assertDatabaseCount('brands', 1);
        $this->assertDatabaseHas('brands', [
            'name' => 'Brand 1',
        ]);
    }

    public function test_no_rollback_database_transaction_when_a_row_failed(): void
    {
        Excel::import(new ProductsImport($this->import), 'test_3.xlsx', 'tests');

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseHas('products', [
            'code' => 111111,
            'name' => 'Test 1',
            'description' => 'Test 1',
            'price' => 10,
            'stock' => 10,
        ]);
    }

    public function test_no_store_errors_in_database_when_the_import_process_finished_properly(): void
    {
        Excel::import(new ProductsImport($this->import), 'test_1.xlsx', 'tests');
        $this->import->refresh();
        $this->assertEquals([], $this->import->errors);
    }

    public function test_store_errors_in_database_when_the_import_process_failed(): void
    {
        Excel::import(new ProductsImport($this->import), 'test_3.xlsx', 'tests');
        $this->import->refresh();
        $this->assertNotEmpty($this->import->errors);
    }

    public function test_import_data_from_a_empty_file(): void
    {
        Excel::import(new ProductsImport($this->import), 'test_4.xlsx', 'tests');
        $this->assertDatabaseCount('products', 0);
        $this->import->refresh();
        $this->assertEquals(JobsByUserStatus::COMPLETED, $this->import->status);
    }
}
