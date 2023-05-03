<?php

namespace App\Actions\Customers;

use App\Actions\Users\UpdateUserAction;
use App\Contracts\Actions\Customers\UpdateCustomer;
use App\Models\Customer;

class UpdateCustomerAction implements UpdateCustomer
{

    public function execute(int $id, array $data): void
    {
        $action = new UpdateUserAction();
        $customer = Customer::query()->findOrFail($id);

        $action->execute($customer->getAttribute('user_id'), ['email' => $data['email']]);

        $customer->fill($data);

        $customer->save();
    }
}
