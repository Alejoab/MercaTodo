<?php

namespace Tests\Unit\Commands;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Models\JobsByUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\UserTestCase;

class DeleteExportFilesUnitTest extends UserTestCase
{
    use RefreshDatabase;

    private JobsByUser $job;

    public function setUp(): void
    {
        parent::setUp();

        /**
         * @var JobsByUser $job
         */
        $job = JobsByUser::query()->create([
            'user_id' => $this->admin->id,
            'type' => JobsByUserType::EXPORT,
            'status' => JobsByUserStatus::COMPLETED,
            'file_name' => 'test-file.csv',
        ]);
        $this->job = $job;

        $file = UploadedFile::fake()->create($job->file_name);
        Storage::disk('exports')->put($job->file_name, $file->getContent());
    }

    public function test_command_delete_export_files()
    {
        $this->travel(config('filesystems.export_expiration') + 1)->minutes();

        $this->artisan('app:delete-export-files');
        Storage::disk('exports')->assertMissing($this->job->file_name);
        $this->assertDatabaseCount('jobs_by_users', 0);
    }

    public function test_command_no_delete_export_files_when_this_is_no_expired()
    {
        $this->travel(config('filesystems.export_expiration') - 1)->minutes();

        $this->artisan('app:delete-export-files');
        Storage::disk('exports')->assertExists($this->job->file_name);
        $this->assertDatabaseCount('jobs_by_users', 1);
    }

    public function test_dont_trow_error_when_there_is_no_file()
    {
        $this->travel(config('filesystems.export_expiration') + 1)->minutes();
        Storage::disk('exports')->delete($this->job->file_name);

        $this->artisan('app:delete-export-files');
        Storage::disk('exports')->assertMissing($this->job->file_name);
        $this->assertDatabaseCount('jobs_by_users', 0);
    }

    public function test_dont_delete_job_when_this_is_pending(): void
    {
        $this->job->status = JobsByUserStatus::PENDING;
        $this->job->save();

        $this->travel(config('filesystems.export_expiration') + 1)->minutes();
        $this->artisan('app:delete-export-files');

        Storage::disk('exports')->assertExists($this->job->file_name);
        $this->assertDatabaseCount('jobs_by_users', 1);
    }

    public function test_delete_failed_job_when_this_is_expired(): void
    {
        $this->job->status = JobsByUserStatus::FAILED;
        $this->job->save();

        $this->travel(config('filesystems.export_expiration') + 1)->minutes();
        $this->artisan('app:delete-export-files');

        Storage::disk('exports')->assertMissing($this->job->file_name);
        $this->assertDatabaseCount('jobs_by_users', 0);
    }

    public function test_delete_failed_job_with_no_file_when_this_is_expired(): void
    {
        $this->job->status = JobsByUserStatus::FAILED;
        $this->job->save();
        Storage::disk('exports')->delete($this->job->file_name);

        $this->travel(config('filesystems.export_expiration') + 1)->minutes();
        $this->artisan('app:delete-export-files');

        Storage::disk('exports')->assertMissing($this->job->file_name);
        $this->assertDatabaseCount('jobs_by_users', 0);
    }
}
