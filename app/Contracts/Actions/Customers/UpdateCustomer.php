<?php

namespace App\Contracts\Actions\Customers;

use App\Models\User;

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
