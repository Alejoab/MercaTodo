<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface UpdateUserRole
{
    public function execute(User $user, string $role): void;
}
