<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\UpdateUserRole;
use App\Exceptions\ApplicationException;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserRoleAction implements UpdateUserRole
{
    /**
     * @throws ApplicationException
     */
    public function execute(User $user, string $role): void
    {
        try {
            if (!$user->hasRole($role)) {
                $user->syncRoles($role);

                Log::warning('[ROLE]', [
                    'admin_id' => auth()->user()->getAuthIdentifier(),
                    'user_id' => $user->getKey(),
                    'role' => $role,
                ]);
            }

            $user->save();
        } catch (Throwable $e) {
            throw new ApplicationException($e);
        }
    }
}
