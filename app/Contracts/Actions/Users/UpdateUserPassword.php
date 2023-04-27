<?php

namespace App\Contracts\Actions\Users;

interface UpdateUserPassword
{
    public function execute(int $id, string $password): void;
}
