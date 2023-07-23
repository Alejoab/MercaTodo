<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface UpdateUserPassword
{
    public function execute(User $user, string $password): void;
}
