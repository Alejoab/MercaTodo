<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface DeleteUser
{
    /**
     * Deletes a user.
     *
     * @param User $user
     *
     * @return void
     */
    public function execute(User $user): void;
}
