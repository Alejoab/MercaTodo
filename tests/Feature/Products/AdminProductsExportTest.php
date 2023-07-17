<?php

namespace Products;

use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\UserTestCase;

class AdminProductsExportTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Brand::factory()->count(2)->create();
        Category::factory()->count(2)->create();
        Product::factory()->count(5)->create();
    }

    public function test_only_admin_can_export_products(): void
    {
        $response = $this->actingAs($this->customer)->post(route('admin.products.export'));

        $response->assertStatus(403);
    }

    public function test_export_products_with_queue(): void
    {
        Excel::fake();

        $response = $this->postJson(route('admin.products.export'));

        $response->assertOk();

        Excel::assertQueued("products_export_{$this->admin->id}.xlsx", 'exports', function ($export) {
            return $export->query()->count() === 5;
        });

        Excel::assertQueuedWithChain([
            CompleteJobsByUser::class,
        ]);
    }

    public function test_export_products_with_filters(): void
    {
        Excel::fake();

        $filter = Category::query()->first()->id;

        $count = Product::query()->filterCategory($filter)->count();

        $response = $this->post(route('admin.products.export', ['category' => $filter,]));

        $response->assertOk();

        Excel::assertQueued("products_export_{$this->admin->id}.xlsx", 'exports', function ($export) use ($count) {
            return $export->query()->count() === $count;
        });
    }

    public function test_try_export_when_an_export_is_already_queued(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.products.export'));
        $response->assertOk();

        $response = $this->post(route('admin.products.export'));
        $response->assertSessionHasErrors();
    }

    public function test_check_export_status(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.products.export'));
        $response->assertOk();

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

        $this->post(route('admin.products.export'));

        $this->assertDatabaseHas('jobs_by_users', [
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_database_has_only_one_export_for_each_user(): void
    {
        Excel::fake();

        $this->post(route('admin.products.export'));

        $export = JobsByUser::query()->first();
        $export->status = JobsByUserStatus::COMPLETED;
        $export->save();

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::COMPLETED,
        ]);

        $this->post(route('admin.products.export'));

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_try_to_download_an_export_when_this_was_not_generated(): void
    {
        $response = $this->get(route('admin.products.export.download'));
        $response->assertNotFound();
    }

    public function test_change_status_of_the_job_when_this_has_failed(): void
    {
        Excel::fake();
        Excel::shouldReceive('queue')->once()->andReturn(new Exception());

        $response = $this->post(route('admin.products.export'));

        $response->assertSessionHasErrors(['app']);
        $this->assertDatabaseHas('jobs_by_users', [
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::FAILED,
        ]);
    }
}
