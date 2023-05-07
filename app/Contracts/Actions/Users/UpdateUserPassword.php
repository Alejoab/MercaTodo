<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface UpdateUserPassword
{
    public function execute(User $user, string $password): void;
}
