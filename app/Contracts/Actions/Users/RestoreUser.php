<?php

namespace App\Contracts\Actions\Users;

interface RestoreUser
{
    public function execute(int $id): void;
}
