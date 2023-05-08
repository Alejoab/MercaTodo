<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface ForceDeleteUser
{
    /**
     * Force deletes a user.
     *
     * @param User $user
     *
     * @return void
     */
    public function execute(User $user): void;
}
