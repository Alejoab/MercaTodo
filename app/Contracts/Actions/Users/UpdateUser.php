<?php

namespace App\Contracts\Actions\Users;

interface UpdateUser
{
    public function execute(int $id, array $data): void;
}
