<?php

namespace Products;

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
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProductsExportTest extends TestCase
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

        Brand::factory()->count(2)->create();
        Category::factory()->count(2)->create();
        Product::factory()->count(5)->create();

        $this->actingAs($this->admin);
    }

    public function test_only_admin_can_export_products(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.products.export'));

        $response->assertStatus(403);
    }

    public function test_export_products_with_queue(): void
    {
        Excel::fake();

        $response = $this->getJson(route('admin.products.export'));

        $response->assertOk();
        $response->assertJsonStructure(['message']);

        Excel::assertQueued("products_export_{$this->admin->id}.xlsx", 'exports', function ($export) {
            return $export->query()->count() === 5;
        });
    }

    public function test_export_products_with_filters(): void
    {
        Excel::fake();

        $filter = Category::query()->first()->id;

        $count = Product::query()->filterCategory($filter)->count();

        $response = $this->getJson(
            route('admin.products.export', [
                'category' => $filter,
            ])
        );

        $response->assertOk();
        $response->assertJsonStructure(['message']);

        Excel::assertQueued("products_export_{$this->admin->id}.xlsx", 'exports', function ($export) use ($count) {
            return $export->query()->count() === $count;
        });
    }

    public function test_try_export_when_an_export_is_already_queued(): void
    {
        Excel::fake();

        $response = $this->getJson(route('admin.products.export'));
        $response->assertOk();
        $response->assertJsonStructure(['message']);

        $response = $this->getJson(route('admin.products.export'));
        $response->assertStatus(400);
        $response->assertJsonStructure(['error']);
    }

    public function test_check_export_status(): void
    {
        Excel::fake();

        $response = $this->getJson(route('admin.products.export'));
        $response->assertOk();
        $response->assertJsonStructure(['message']);

        $export = JobsByUser::query()->first();

        $response = $this->getJson(route('admin.products.exports.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::PENDING->value]);

        $export->status = JobsByUserStatus::COMPLETED;
        $export->save();

        $response = $this->getJson(route('admin.products.exports.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::COMPLETED->value]);

        $export->status = JobsByUserStatus::FAILED;
        $export->save();

        $response = $this->getJson(route('admin.products.exports.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::FAILED->value]);
    }

    public function test_check_export_status_with_no_exports()
    {
        $response = $this->getJson(route('admin.products.exports.check'));
        $response->assertOk();
        $response->assertJson([]);
    }

    public function test_database_stores_export(): void
    {
        Excel::fake();

        $this->getJson(route('admin.products.export'));

        $this->assertDatabaseHas('jobs_by_users', [
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_database_has_only_one_export_for_each_user(): void
    {
        Excel::fake();

        $this->getJson(route('admin.products.export'));

        $export = JobsByUser::query()->first();
        $export->status = JobsByUserStatus::COMPLETED;
        $export->save();

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::COMPLETED,
        ]);

        $this->getJson(route('admin.products.export'));

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }
}
