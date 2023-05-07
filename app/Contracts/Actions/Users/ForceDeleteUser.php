<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface ForceDeleteUser
{
    public function execute(User $user): void;
}
