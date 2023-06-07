<?php

namespace App\Actions\Customers;

use App\Actions\Users\UpdateUserAction;
use App\Contracts\Actions\Customers\UpdateCustomer;
use App\Exceptions\ApplicationException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateCustomerAction implements UpdateCustomer
{
    /**
     * @throws ApplicationException
     */
    public function execute(User $user, array $data): void
    {
        try {
            DB::beginTransaction();

            $action = new UpdateUserAction();
            $action->execute($user, ['email' => $data['email'],]);
            $user->customer->fill($data);
            $user->customer->save();

            DB::commit();
        } catch (ApplicationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new ApplicationException($e);
        }
    }
}
