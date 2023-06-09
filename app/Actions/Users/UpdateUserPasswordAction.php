<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\UpdateUserPassword;
use App\Exceptions\ApplicationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserPasswordAction implements UpdateUserPassword
{
    /**
     * @throws ApplicationException
     */
    public function execute(User $user, string $password): void
    {
        try {
            $user->password = Hash::make($password);

            Log::warning('[PASSWORD]', [
                'admin_id' => auth()->user()->getAuthIdentifier(),
                'user_id' => $user->getKey(),
            ]);

            $user->save();
        } catch (Throwable $e) {
            throw new ApplicationException($e, $user->toArray());
        }
    }
}
