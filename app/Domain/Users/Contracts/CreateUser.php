<?php

namespace App\Domain\Users\Contracts;

interface CreateUser
{
    /**
     * Creates a new user.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function execute(array $data): mixed;
}
