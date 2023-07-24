<?php

namespace App\Console\Commands;

use App\Support\Models\JobsByUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class deleteExportFiles extends Command
{
    protected $signature = 'app:delete-export-files';

    protected $description = 'Delete export files';

    public function handle(): void
    {
        $exports = JobsByUser::query()->whereCompletedStatus()->get();

        /**
         * @var JobsByUser $export
         */
        foreach ($exports as $export) {
            if ($export->created_at->diffInMinutes(now()) < config('filesystems.export_expiration')) {
                continue;
            }

            $fileName = $export->file_name;
            Storage::disk('exports')->delete($fileName);

            $export->delete();
        }
    }
}
