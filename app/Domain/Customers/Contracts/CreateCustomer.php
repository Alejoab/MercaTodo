<?php

namespace App\Domain\Customers\Contracts;

interface CreateCustomer
{
    public function execute(array $data): mixed;
}
