<?php

namespace App\Support\Jobs;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Mails\JobsByUserMail;
use App\Support\Models\JobsByUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class CompleteJobsByUser implements ShouldQueue
{
    use Queueable, Dispatchable;

    private JobsByUser $job;

    public function __construct(JobsByUser $job)
    {
        $this->job = $job;
    }

    public function handle(): void
    {
        $this->job->status = JobsByUserStatus::COMPLETED;
        $this->job->save();

        Mail::to($this->job->user->email)->queue(new JobsByUserMail($this->job));
    }
}
