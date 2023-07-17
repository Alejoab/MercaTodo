<?php

namespace App\Console\Commands;

use App\Support\Models\JobsByUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class deleteExportFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-export-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
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
