<?php

namespace App\Domain\Customers\Actions;

use App\Domain\Customers\Contracts\CreateCustomer;
use App\Domain\Customers\Models\Customer;
use App\Domain\Users\Actions\CreateUserAction;
use App\Support\Exceptions\ApplicationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateCustomerAction implements CreateCustomer
{

    /**
     * @throws ApplicationException
     */
    public function execute(array $data): Builder|Model
    {
        try {
            DB::beginTransaction();

            $user = (new CreateUserAction())->execute([
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $customer = Customer::query()->create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'document' => $data['document'],
                'document_type' => $data['document_type'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city_id' => $data['city_id'],
                'user_id' => $user->getAttribute('id'),
            ]);

            DB::commit();

            return $customer;
        } catch (ApplicationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new ApplicationException($e, $data);
        }
    }
}
