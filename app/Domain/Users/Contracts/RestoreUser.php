<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface RestoreUser
{
    public function execute(User $user): void;
}
