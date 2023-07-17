<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface UpdateUserRole
{
    /**
     * Updates the given user's role.
     *
     * @param User   $user
     * @param string $role
     * @param array  $permissions
     *
     * @return void
     */
    public function execute(User $user, string $role, array $permissions): void;
}
