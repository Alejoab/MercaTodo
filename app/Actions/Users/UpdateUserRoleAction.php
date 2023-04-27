<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\UpdateUserRole;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UpdateUserRoleAction implements UpdateUserRole
{

    public function execute(int $id, string $role): void
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!$user->hasRole($role)) {
            $user->syncRoles($role);

            Log::warning('[ROLE]', [
                'admin_id' => auth()->user()->getAuthIdentifier(),
                'user_id' => $id,
                'role' => $role,
            ]);
        }

        $user->save();
    }
}
