<?php

namespace App\Contracts\Actions\Users;

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
