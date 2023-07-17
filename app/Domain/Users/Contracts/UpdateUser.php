<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface UpdateUser
{
    /**
     * Updates the user's information.
     *
     * @param User  $user
     * @param array $data
     *
     * @return void
     */
    public function execute(User $user, array $data): void;
}
