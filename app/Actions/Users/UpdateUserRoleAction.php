<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\UpdateUserRole;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UpdateUserRoleAction implements UpdateUserRole
{
    public function execute(User $user, string $role): void
    {
        if (!$user->hasRole($role)) {
            $user->syncRoles($role);

            Log::warning('[ROLE]', [
                'admin_id' => auth()->user()->getAuthIdentifier(),
                'user_id' => $user->id,
                'role' => $role,
            ]);
        }

        $user->save();
    }
}
