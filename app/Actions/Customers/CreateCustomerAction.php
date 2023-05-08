<?php

namespace App\Actions\Customers;

use App\Actions\Users\CreateUserAction;
use App\Contracts\Actions\Customers\CreateCustomer;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreateCustomerAction implements CreateCustomer
{

    public function execute(array $data): Builder|Model
    {
        $action = new CreateUserAction();

        $user = $action->execute([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return Customer::query()->create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'document' => $data['document'],
            'document_type' => $data['document_type'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city_id' => $data['city_id'],
            'user_id' => $user->getAttribute('id'),
        ]);
    }
}
