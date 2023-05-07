<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface DeleteUser
{
    public function execute(User $user): void;
}
