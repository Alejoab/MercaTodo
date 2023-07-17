<?php

namespace Tests\Unit\Reports;

use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Customer;
use App\Domain\Customers\Models\Department;
use App\Domain\Orders\Models\Order;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Reports\Classes\SalesByBrand;
use App\Domain\Reports\Classes\SalesByCategory;
use App\Domain\Reports\Classes\OrdersByDepartment;
use App\Domain\Reports\Classes\OrdersByPaymentMethodAndStatus;
use App\Domain\Reports\Classes\SalesByProduct;
use App\Domain\Reports\Enums\ReportType;
use App\Domain\Reports\Jobs\ReportExport;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\UserTestCase;

class AdminReportUnitTest extends UserTestCase
{
    use RefreshDatabase;

    private JobsByUser $report;

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

        /**
         * @var JobsByUser $report
         */
        $report = JobsByUser::query()->create([
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::REPORT,
            'status' => JobsByUserStatus::PENDING,
            'file_name' => 'test.xlsx',
        ]);
        $this->report = $report;
    }

    public function test_store_report(): void
    {
        Excel::store(new ReportExport($this->report, [ReportType::R1->value], null, null), $this->report->file_name, 'exports');

        Storage::disk('exports')->assertExists($this->report->file_name);
    }

    public function test_report_change_value_in_database(): void
    {
        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->report->id,
            'status' => JobsByUserStatus::PENDING,
        ]);

        CompleteJobsByUser::dispatch($this->report);

        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->report->id,
            'status' => JobsByUserStatus::COMPLETED,
        ]);
    }

    public function test_sales_by_category(): void
    {
        $report = new SalesByCategory($this->report, null, null);

        $this->assertLessThanOrEqual(2, $report->query()->get()->count());
    }

    public function test_sales_by_brand(): void
    {
        $report = new SalesByBrand($this->report, null, null);
        $this->assertLessThanOrEqual(2, $report->query()->get()->count());
    }

    public function test_sales_by_department(): void
    {
        $report = new OrdersByDepartment($this->report, null, null);
        $this->assertEquals(1, $report->query()->get()->count());
    }

    public function test_sales_by_payment_method_and_status(): void
    {
        $report = new OrdersByPaymentMethodAndStatus($this->report, null, null);
        $this->assertLessThanOrEqual(6, $report->query()->get()->count());
    }

    public function test_sales_by_product(): void
    {
        $report = new SalesByProduct($this->report, null, null);
        $this->assertLessThanOrEqual(5, $report->query()->get()->count());
    }
}
