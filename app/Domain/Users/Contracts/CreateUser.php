<?php

namespace App\Domain\Users\Contracts;

interface CreateUser
{
    public function execute(array $data): mixed;
}
