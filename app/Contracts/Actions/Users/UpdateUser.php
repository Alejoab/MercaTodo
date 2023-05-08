<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

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
