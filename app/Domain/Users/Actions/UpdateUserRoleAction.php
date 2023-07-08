<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Contracts\UpdateUserRole;
use App\Domain\Users\Models\User;
use App\Support\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserRoleAction implements UpdateUserRole
{
    /**
     * @param User   $user
     * @param string $role
     * @param array  $permissions
     *
     * @throws ApplicationException
     */
    public function execute(User $user, string $role, array $permissions): void
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

            $user->syncPermissions($permissions);

            $user->save();
        } catch (Throwable $e) {
            throw new ApplicationException($e, [
                'user' => $user->toArray(),
                'role' => $role,
            ]);
        }
    }
}
