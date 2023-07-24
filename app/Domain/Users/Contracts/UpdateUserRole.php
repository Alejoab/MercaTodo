<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface UpdateUserRole
{
    public function execute(User $user, string $role, array $permissions): void;
}
