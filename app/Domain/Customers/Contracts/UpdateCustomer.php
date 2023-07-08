<?php

namespace App\Domain\Customers\Contracts;

use App\Domain\Users\Models\User;

interface UpdateCustomer
{
    /**
     * Updates a customer.
     *
     * @param User  $user
     * @param array $data
     *
     * @return void
     */
    public function execute(User $user, array $data): void;
}
