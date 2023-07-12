<?php

namespace App\Support\Jobs;

use App\Support\Models\JobsByUser;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompleteJobByUser implements ShouldQueue
{
    private JobsByUser $job;

    public function __construct(JobsByUser $job)
    {
        $this->job = $job;
    }

    
}
