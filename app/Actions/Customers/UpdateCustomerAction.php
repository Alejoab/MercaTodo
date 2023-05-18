<?php

namespace App\Actions\Customers;

use App\Actions\Users\UpdateUserAction;
use App\Contracts\Actions\Customers\UpdateCustomer;
use App\Models\User;

class UpdateCustomerAction implements UpdateCustomer
{
    public function execute(User $user, array $data): void
    {
        $action = new UpdateUserAction();
        $action->execute($user, ['email' => $data['email'],]);

        $user->customer->fill($data);
        $user->customer->save();
    }
}
