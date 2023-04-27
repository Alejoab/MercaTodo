<?php

namespace App\Contracts\Actions\Users;

interface DeleteUser
{
    public function execute(int $id): void;
}
