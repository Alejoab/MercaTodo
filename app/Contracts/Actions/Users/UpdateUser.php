<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface UpdateUser
{
    public function execute(User $user, array $data): void;
}
