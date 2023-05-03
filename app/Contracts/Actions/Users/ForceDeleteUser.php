<?php

namespace App\Contracts\Actions\Users;

interface ForceDeleteUser
{
    public function execute(int $id): void;
}
