<?php

namespace Tests\Unit\Administrator;

use App\Console\Jobs\ProductsExport;
use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Department;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Models\JobsByUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductProductsExportUnitTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private JobsByUser $export;
    private string $fileName;

    public function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::create(['name' => RoleEnum::SUPER_ADMIN->value]);
        Department::factory(1)->create();
        City::factory(1)->create();
        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);

        $this->export = JobsByUser::create([
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
        $this->fileName = "test.xlsx";

        Brand::factory()->count(2)->create();
        Category::factory()->count(2)->create();
        Product::factory()->count(5)->create();
    }

    public function test_store_export(): void
    {
        Storage::fake('exports');

        Excel::store(new ProductsExport($this->export, null, null, null), $this->fileName, 'exports');

        Storage::disk('exports')->assertExists($this->fileName);
    }

    public function test_export_change_value_in_database(): void
    {
        Storage::fake('exports');

        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->export->id,
            'status' => JobsByUserStatus::PENDING,
        ]);

        Excel::store(new ProductsExport($this->export, null, null, null), $this->fileName, 'exports');

        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->export->id,
            'status' => JobsByUserStatus::COMPLETED,
        ]);
    }

}
