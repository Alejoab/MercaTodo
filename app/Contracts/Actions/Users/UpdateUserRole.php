<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface UpdateUserRole
{
    /**
     * Updates the given user's role.
     *
     * @param User   $user
     * @param string $role
     *
     * @return void
     */
    public function execute(User $user, string $role): void;
}
