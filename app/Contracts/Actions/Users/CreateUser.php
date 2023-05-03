<?php

namespace App\Contracts\Actions\Users;

use App\Models\User;

interface CreateUser
{
    public function execute(array $data): mixed;
}
