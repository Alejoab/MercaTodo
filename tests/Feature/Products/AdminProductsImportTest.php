<?php

namespace Products;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Models\JobsByUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\UserTestCase;

class AdminProductsImportTest extends UserTestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_import_products(): void
    {
        $response = $this->actingAs($this->customer)->post(route('admin.products.import'));

        $response->assertStatus(403);
    }

    public function test_csv_files_are_allowed_to_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();
    }

    public function test_xlsx_files_are_allowed_to_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.xlsx');

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();
    }

    public function test_xls_files_are_allowed_to_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.xls');

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();
    }

    public function test_no_spreadsheet_files_are_not_allowed_to_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.png');

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertSessionHasErrors();
    }

    public function test_try_import_when_an_import_is_already_queued(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertSessionHasErrors();
    }

    public function test_check_import_status(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $response = $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();

        $import = JobsByUser::query()->first();

        $response = $this->getJson(route('admin.products.import.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::PENDING->value]);

        $import->status = JobsByUserStatus::COMPLETED;
        $import->save();

        $response = $this->getJson(route('admin.products.import.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::COMPLETED->value]);

        $import->status = JobsByUserStatus::FAILED;
        $import->save();

        $response = $this->getJson(route('admin.products.import.check'));
        $response->assertOk();
        $response->assertJson(['status' => JobsByUserStatus::FAILED->value]);
    }

    public function test_check_import_status_with_no_imports()
    {
        $response = $this->getJson(route('admin.products.import.check'));
        $response->assertOk();
        $response->assertJson([]);
    }

    public function test_database_stores_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);

        $this->assertDatabaseHas('jobs_by_users', [
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::IMPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }

    public function test_database_has_only_one_import_for_each_user(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);

        $import = JobsByUser::query()->first();
        $import->status = JobsByUserStatus::COMPLETED;
        $import->save();

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::IMPORT,
            'status' => JobsByUserStatus::COMPLETED,
        ]);

        $this->post(route('admin.products.import'), [
            'file' => $file,
        ]);

        $this->assertDatabaseCount('jobs_by_users', 1);
        $this->assertDatabaseHas('jobs_by_users', [
            'type' => JobsByUserType::IMPORT,
            'status' => JobsByUserStatus::PENDING,
        ]);
    }
}
