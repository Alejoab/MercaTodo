<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class UsersService
{
    public function listUsersToTable(?string $search): LengthAwarePaginator
    {
        // TODO: withTrashed()
        return User::withTrashed()
            ->withRoles()
            ->withoutUser(auth()->user()->getAuthIdentifier())
            ->contains($search, ['users.email'])
            ->select(
                [
                    'users.id',
                    'users.email',
                    'roles.name as role',
                    'users.deleted_at as deleted',
                ]
            )
            ->orderBy('users.id')
            ->paginate(50);
    }
}
