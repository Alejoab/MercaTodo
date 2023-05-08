<?php

namespace App\Contracts\Actions\Customers;

interface CreateCustomer
{
    /**
     * Creates a new customer.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function execute(array $data): mixed;
}
