<?php

namespace App\Domain\Customers\Actions;

use App\Domain\Customers\Contracts\CreateCustomer;
use App\Domain\Customers\Models\Customer;
use App\Domain\Users\Actions\CreateUserAction;
use App\Domain\Users\Models\User;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateCustomerAction implements CreateCustomer
{

    /**
     * @throws CustomException
     */
    public function execute(array $data): Customer
    {
        try {
            DB::beginTransaction();

            $user = (new CreateUserAction())->execute([
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            /**
             * @var Customer $customer
             */
            $customer = Customer::query()->create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'document' => $data['document'],
                'document_type' => $data['document_type'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city_id' => $data['city_id'],
                'user_id' => $user->id,
            ]);

            DB::commit();

            return $customer;
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                throw $e;
            }

            throw new ApplicationException($e, $data);
        }
    }
}
