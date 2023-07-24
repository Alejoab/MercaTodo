<?php

namespace App\Support\Contracts;

use App\Support\Enums\JobsByUserType;
use App\Support\Models\JobsByUser;

interface CreateJobsByUser
{
    public function execute(int $userId, JobsByUserType $type, string $fileName = null): JobsByUser;
}
