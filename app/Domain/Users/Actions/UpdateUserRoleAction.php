<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Contracts\UpdateUserRole;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserRoleAction implements UpdateUserRole
{
    /**
     * @throws CustomException
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

            if ($role === RoleEnum::ADMIN->value) {
                $user->syncPermissions($permissions);
            } else {
                $user->syncPermissions([]);
            }

            $user->save();
        } catch (Throwable $e) {
            throw new ApplicationException($e, [
                'user' => $user->toArray(),
                'role' => $role,
            ]);
        }
    }
}
