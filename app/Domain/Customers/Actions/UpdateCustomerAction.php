<?php

namespace App\Domain\Customers\Actions;

use App\Domain\Customers\Contracts\UpdateCustomer;
use App\Domain\Users\Actions\UpdateUserAction;
use App\Domain\Users\Models\User;
use App\Exceptions\ApplicationException;
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

            (new UpdateUserAction())->execute($user, ['email' => $data['email'],]);
            $user->customer->fill($data);
            $user->customer->save();

            DB::commit();
        } catch (ApplicationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new ApplicationException($e, [
                'user' => $user->toArray(),
                'data' => $data,
            ]);
        }
    }
}
