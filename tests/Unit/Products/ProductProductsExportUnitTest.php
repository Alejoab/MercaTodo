<?php

namespace Tests\Unit\Products;

use App\Domain\Products\Jobs\ProductsExport;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Jobs\CompleteJobsByUser;
use App\Support\Models\JobsByUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\UserTestCase;

class ProductProductsExportUnitTest extends UserTestCase
{
    use RefreshDatabase;

    private JobsByUser $export;
    private string $fileName;

    public function setUp(): void
    {
        parent::setUp();

        Brand::factory()->count(2)->create();
        Category::factory()->count(2)->create();
        Product::factory()->count(5)->create();

        /**
         * @var JobsByUser $export
         */
        $export = JobsByUser::create([
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
        $this->export = $export;
        $this->fileName = "test.xlsx";
    }

    public function test_store_export(): void
    {
        Excel::store(new ProductsExport($this->export, null, null, null), $this->fileName, 'exports');

        Storage::disk('exports')->assertExists($this->fileName);
    }

    public function test_export_change_value_in_database(): void
    {
        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->export->id,
            'status' => JobsByUserStatus::PENDING,
        ]);

        CompleteJobsByUser::dispatch($this->export);

        $this->assertDatabaseHas('jobs_by_users', [
            'id' => $this->export->id,
            'status' => JobsByUserStatus::COMPLETED,
        ]);
    }

}
