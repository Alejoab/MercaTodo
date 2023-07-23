<?php

namespace App\Domain\Customers\Contracts;

use App\Domain\Users\Models\User;

interface UpdateCustomer
{
    public function execute(User $user, array $data): void;
}
