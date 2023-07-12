<?php

namespace App\Support\Jobs;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Models\JobsByUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompleteJobsByUser implements ShouldQueue
{
    use Queueable;

    private JobsByUser $job;

    public function __construct(JobsByUser $job)
    {
        $this->job = $job;
    }

    public function handle(): void
    {
        $this->job->status = JobsByUserStatus::COMPLETED;
        $this->job->save();
    }
}
