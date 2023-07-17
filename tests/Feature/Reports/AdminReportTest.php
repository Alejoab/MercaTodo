<?php

namespace Tests\Feature\Reports;

use App\Domain\Orders\Models\Order;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Reports\Enums\ReportType;
use App\Domain\Reports\Jobs\ReportExport;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\UserTestCase;

class AdminReportTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Brand::factory()->count(2)->create();
        Category::factory()->count(2)->create();
        Product::factory()->count(5)->create();
        Order::factory()->count(5)->create();
    }

    public function test_only_admin_can_generate_reports(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports'));
        $response->assertOk();

        $response = $this->actingAs($this->customer)->get(route('admin.reports'));
        $response->assertStatus(403);
    }

    public function test_generate_reports_with_queue(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.reports.generate'), [
            'reports' => [
                ReportType::R1->value,
            ],
        ]);
        $response->assertOk();

        Excel::assertQueued("report_{$this->admin->id}.xlsx", 'exports');

        Excel::assertQueuedWithChain([
            CompleteJobsByUser::class,
        ]);
    }

    public function test_generate_reports_with_invalid_data(): void
    {
        $response = $this->post(
            route('admin.reports.generate', [
                'from' => now()->addDays(2)->format('Y-m-d'),
                'to' => now()->addDay()->format('Y-m-d'),
                'reports' => [
                    'Test',
                ],
            ])
        );

        $response->assertSessionHasErrors(['from', 'to', 'reports']);
    }

    public function test_try_generate_a_reports_when_a_report_is_already_queued(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.reports.generate'), [
            'reports' => [
                ReportType::R1->value,
            ],
        ]);
        $response->assertOk();

        $response = $this->post(route('admin.reports.generate'));
        $response->assertSessionHasErrors();
    }

    public function test_check_report_status(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.reports.generate'), [
            'reports' => [
                ReportType::R1->value,
            ],
        ]);
        $response->assertOk();

        $report = JobsByUser::query()->first();

        $response = $this->getJson(route('admin.reports.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::PENDING->value]);

        $report->status = JobsByUserStatus::COMPLETED;
        $report->save();

        $response = $this->getJson(route('admin.reports.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::COMPLETED->value]);

        $report->status = JobsByUserStatus::FAILED;
        $report->save();

        $response = $this->getJson(route('admin.reports.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::FAILED->value]);
    }

    public function test_check_report_status_with_no_reports()
    {
        $response = $this->getJson(route('admin.reports.check'));
        $response->assertOk();
        $response->assertJson([]);
    }

    public function test_database_stores_report(): void
    {
        Excel::fake();

        $this->post(route('admin.reports.generate'), [
            'reports' => [
                ReportType::R1->value,
            ],
        ]);

        $this->assertDatabaseHas('jobs_by_users', [
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::REPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_database_has_only_one_report_for_each_user(): void
    {
        Excel::fake();

        $this->post(route('admin.reports.generate'), [
            'reports' => [
                ReportType::R1->value,
            ],
        ]);

        $report = JobsByUser::query()->first();
        $report->status = JobsByUserStatus::COMPLETED;
        $report->save();

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::REPORT,
            'status' => JobsByUserStatus::COMPLETED,
        ]);

        $this->post(route('admin.reports.generate'), [
            'reports' => [
                ReportType::R1->value,
            ],
        ]);

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::REPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_try_to_download_the_reports_when_this_was_not_generated(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.download'));
        $response->assertNotFound();
    }

    public function test_report_only_has_the_requested_types(): void
    {
        Excel::fake();

        $response = $this->post(route('admin.reports.generate'), [
            'reports' => [
                ReportType::R1->value,
                ReportType::R2->value,
            ],
        ]);
        $response->assertOk();

        Excel::assertQueued("report_{$this->admin->id}.xlsx", 'exports', function (ReportExport $export) {
            return count($export->sheets()) === 2
                && $export->sheets()[0]->title() === ReportType::R1->value
                && $export->sheets()[1]->title() === ReportType::R2->value;
        });
    }

    public function test_change_status_of_the_job_when_this_has_failed(): void
    {
        Excel::fake();
        Excel::shouldReceive('queue')->once()->andReturn(new Exception());

        $response = $this->post(route('admin.reports.generate'), [
            'reports' => [
                ReportType::R1->value,
            ],
        ]);

        $response->assertSessionHasErrors(['app']);
        $this->assertDatabaseHas('jobs_by_users', [
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::REPORT,
            'status' => JobsByUserStatus::FAILED,
        ]);
    }
}
