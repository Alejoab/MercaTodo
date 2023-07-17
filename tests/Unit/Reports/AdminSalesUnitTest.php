<?php

namespace Tests\Unit\Reports;

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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\UserTestCase;

class AdminSalesUnitTest extends UserTestCase
{
    use RefreshDatabase;

    private JobsByUser $sales;

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
            'type' => JobsByUserType::SALES,
            'status' => JobsByUserStatus::PENDING,
            'file_name' => 'test.xlsx',
        ]);
        $this->sales = $report;
    }

    public function test_store_sales(): void
    {
        Excel::store(new SalesExport($this->sales, null, null), $this->sales->file_name, 'exports');

        Storage::disk('exports')->assertExists($this->sales->file_name);
    }

    public function test_sales_export_change_value_in_database(): void
    {
        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->sales->id,
            'status' => JobsByUserStatus::PENDING,
        ]);

        CompleteJobsByUser::dispatch($this->sales);

        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->sales->id,
            'status' => JobsByUserStatus::COMPLETED,
        ]);
    }

    public function test_sales(): void
    {
        $report = new SalesExport($this->sales, null, null);

        $this->assertLessThanOrEqual(Order_detail::query()->count(), $report->query()->get()->count());
    }
}
