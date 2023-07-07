<?php

namespace Tests\Feature\Administrator;

use App\Enums\ExportImportStatus;
use App\Enums\ExportImportType;
use App\Enums\RoleEnum;
use App\Models\City;
use App\Models\Department;
use App\Models\ExportImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminImportTest extends TestCase
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

        $this->actingAs($this->admin);
    }

    public function test_only_admin_can_import_products(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.products.import'));

        $response->assertStatus(403);
    }

    public function test_csv_files_are_allowed_to_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $response = $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();
        $response->assertJsonStructure(['message']);
    }

    public function test_xlsx_files_are_allowed_to_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.xlsx');

        $response = $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();
        $response->assertJsonStructure(['message']);
    }

    public function test_xls_files_are_allowed_to_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.xls');

        $response = $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();
        $response->assertJsonStructure(['message']);
    }

    public function test_no_spreadsheet_files_are_not_allowed_to_import(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.png');

        $response = $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function test_try_import_when_an_import_is_already_queued(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $response = $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();
        $response->assertJsonStructure(['message']);

        $response = $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertStatus(400);
        $response->assertJsonStructure(['error']);
    }

    public function test_check_import_status(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $response = $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);
        $response->assertOk();
        $response->assertJsonStructure(['message']);

        $import = ExportImport::query()->first();

        $response = $this->getJson(route('admin.products.import.check'));
        $response->assertOk();
        $response->assertJson(['status' => ExportImportStatus::PENDING->value]);

        $import->status = ExportImportStatus::COMPLETED;
        $import->save();

        $response = $this->getJson(route('admin.products.import.check'));
        $response->assertOk();
        $response->assertJson(['status' => ExportImportStatus::COMPLETED->value]);

        $import->status = ExportImportStatus::FAILED;
        $import->save();

        $response = $this->getJson(route('admin.products.import.check'));
        $response->assertOk();
        $response->assertJson(['status' => ExportImportStatus::FAILED->value]);
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

        $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);

        $this->assertDatabaseHas('export_imports', [
            'user_id' => $this->admin->id,
            'type' => ExportImportType::IMPORT,
            'status' => ExportImportStatus::PENDING,
        ]);
    }

    public function test_database_has_only_one_import_for_each_user(): void
    {
        Excel::fake();
        $file = UploadedFile::fake()->create('test_1.csv');

        $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);

        $import = ExportImport::query()->first();
        $import->status = ExportImportStatus::COMPLETED;
        $import->save();

        $this->assertDatabaseCount('export_imports', 1);
        $this->assertDatabaseHas('export_imports', [
            'type' => ExportImportType::IMPORT,
            'status' => ExportImportStatus::COMPLETED,
        ]);

        $this->postJson(route('admin.products.import'), [
            'file' => $file,
        ]);

        $this->assertDatabaseCount('export_imports', 1);
        $this->assertDatabaseHas('export_imports', [
            'type' => ExportImportType::IMPORT,
            'status' => ExportImportStatus::PENDING,
        ]);
    }
}
