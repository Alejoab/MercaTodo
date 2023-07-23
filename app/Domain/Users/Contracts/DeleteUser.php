<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface DeleteUser
{
    public function execute(User $user): void;
}
