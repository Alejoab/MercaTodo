<?php

namespace App\Support\Actions;

use App\Support\Contracts\CreateJobsByUser;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\Exceptions\JobsByUserException;
use App\Support\Models\JobsByUser;

class CreateJobsByUserAction implements CreateJobsByUser
{

    /**
     * @throws JobsByUserException
     */
    public function execute(int $userId, JobsByUserType $type, string $fileName = null): JobsByUser
    {
        /**
         * @var JobsByUser $job
         */
        $job = JobsByUser::query()->firstOrCreate([
            'user_id' => $userId,
            'type' => $type,
        ]);

        if ($job->status === JobsByUserStatus::PENDING) {
            throw JobsByUserException::createFromType($type);
        }

        $job->status = JobsByUserStatus::PENDING;
        $job->file_name = $fileName;
        $job->errors = [];
        $job->save();

        return $job;
    }
}
