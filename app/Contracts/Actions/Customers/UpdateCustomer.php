<?php

namespace App\Contracts\Actions\Customers;

interface UpdateCustomer
{
    public function execute(int $id, array $data): void;
}
