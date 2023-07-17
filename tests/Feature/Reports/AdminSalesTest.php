<?php

namespace Tests\Feature\Reports;

use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Customer;
use App\Domain\Customers\Models\Department;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\Order_detail;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Reports\Jobs\SalesExport;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\UserTestCase;

class AdminSalesTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Brand::factory()->count(2)->create();
        Category::factory()->count(2)->create();
        Product::factory()->count(5)->create();
        Order::factory()->count(5)->create();

        Department::factory()->create();
        City::factory()->create();

        Customer::factory()->create([
            'user_id' => $this->admin->id,
        ]);
        Customer::factory()->create([
            'user_id' => $this->customer->id,
        ]);
    }

    public function test_only_admin_can_export_sales(): void
    {
        Excel::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.reports.sales.generate'));
        $response->assertOk();

        $response = $this->actingAs($this->customer)->post(route('admin.reports.sales.generate'));
        $response->assertStatus(403);
    }

    public function test_export_sales_with_queue(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.reports.sales.generate'));
        $response->assertOk();

        Excel::assertQueued("sales_{$this->admin->id}.xlsx", 'exports', function (SalesExport $export) {
            return $export->query()->count() === Order_detail::query()->count();
        });

        Excel::assertQueuedWithChain([
            CompleteJobsByUser::class,
        ]);
    }

    public function test_export_sales_with_filters(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.reports.sales.generate'), [
            'from' => '2023-03-15',
        ]);
        $response->assertOk();

        Excel::assertQueued("sales_{$this->admin->id}.xlsx", 'exports', function (SalesExport $export) {
            return $export->query()->count() === Order_detail::query()->whereDate('created_at', '>=', '2023-03-15')->count();
        });
    }

    public function test_export_sales_with_invalid_data(): void
    {
        $response = $this->post(
            route('admin.reports.generate', [
                'from' => now()->addDays(2)->format('Y-m-d'),
                'to' => now()->addDay()->format('Y-m-d'),
            ])
        );

        $response->assertSessionHasErrors(['from', 'to', 'reports']);
    }

    public function test_try_to_export_sales_when_an_export_is_already_queued(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.reports.sales.generate'));
        $response->assertOk();

        $response = $this->post(route('admin.reports.sales.generate'));
        $response->assertSessionHasErrors();
    }

    public function test_check_sales_export_status(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.reports.sales.generate'));
        $response->assertOk();

        $report = JobsByUser::query()->first();

        $response = $this->getJson(route('admin.reports.sales.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::PENDING->value]);

        $report->status = JobsByUserStatus::COMPLETED;
        $report->save();

        $response = $this->getJson(route('admin.reports.sales.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::COMPLETED->value]);

        $report->status = JobsByUserStatus::FAILED;
        $report->save();

        $response = $this->getJson(route('admin.reports.sales.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::FAILED->value]);
    }

    public function test_check_report_status_with_no_reports()
    {
        $response = $this->getJson(route('admin.reports.sales.check'));
        $response->assertOk();
        $response->assertJson([]);
    }

    public function test_database_stores_sales_export(): void
    {
        Excel::fake();

        $this->post(route('admin.reports.sales.generate'));

        $this->assertDatabaseHas('jobs_by_users', [
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::SALES,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_database_has_only_one_sales_export_for_each_user(): void
    {
        Excel::fake();

        $this->post(route('admin.reports.sales.generate'));

        $report = JobsByUser::query()->first();
        $report->status = JobsByUserStatus::COMPLETED;
        $report->save();

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::SALES,
            'status' => JobsByUserStatus::COMPLETED,
        ]);

        $this->post(route('admin.reports.sales.generate'));

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::SALES,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_try_to_download_the_sales_export_when_this_was_not_generated(): void
    {
        $response = $this->get(route('admin.reports.sales.download'));
        $response->assertNotFound();
    }

    public function test_change_status_of_the_job_when_this_has_failed(): void
    {
        Excel::fake();
        Excel::shouldReceive('queue')->once()->andThrow(Exception::class);

        $response = $this->post(route('admin.reports.sales.generate'));

        $response->assertSessionHasErrors(['app']);
        $this->assertDatabaseHas('jobs_by_users', [
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::SALES,
            'status' => JobsByUserStatus::FAILED,
        ]);
    }
}
