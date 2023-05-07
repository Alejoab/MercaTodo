<?php

namespace App\Contracts\Actions\Customers;

use App\Models\User;

interface UpdateCustomer
{
    public function execute(User $user, array $data): void;
}
