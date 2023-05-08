<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface UpdateUserPassword
{
    /**
     * Updates the user's password.
     *
     * @param User   $user
     * @param string $password
     *
     * @return void
     */
    public function execute(User $user, string $password): void;
}
