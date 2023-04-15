<?php

namespace App\Services;

use App\Models\Customer;

class CustomersService
{
    public function store(array $data): Customer
    {
        $service = new UsersService();

        $user = $service->store($data['email'], $data['password']);

        return Customer::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'document' => $data['document'],
            'document_type' => $data['document_type'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city_id' => $data['city_id'],
            'user_id' => $user->id,
        ]);
    }
}
