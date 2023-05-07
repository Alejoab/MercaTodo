<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface RestoreUser
{
    public function execute(User $user): void;
}
