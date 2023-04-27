<?php

namespace App\Contracts\Actions\Users;

interface UpdateUserRole
{
    public function execute(int $id, string $role): void;
}
