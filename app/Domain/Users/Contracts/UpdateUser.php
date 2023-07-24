<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface UpdateUser
{
    public function execute(User $user, array $data): void;
}
