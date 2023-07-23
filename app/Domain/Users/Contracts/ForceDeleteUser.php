<?php

namespace App\Domain\Users\Contracts;

use App\Domain\Users\Models\User;

interface ForceDeleteUser
{
    public function execute(User $user): void;
}
