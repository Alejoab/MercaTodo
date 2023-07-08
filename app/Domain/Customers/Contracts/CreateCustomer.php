<?php

namespace App\Domain\Customers\Contracts;

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
