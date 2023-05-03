<?php

namespace App\Contracts\Actions\Customers;

interface CreateCustomer
{
    public function execute(array $data): mixed;
}
