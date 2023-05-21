<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class UsersService
{
    /**
     * Lists users to the admin
     *
     * @param string|null $search
     *
     * @return LengthAwarePaginator
     */
    public function listUsersToTable(?string $search): LengthAwarePaginator
    {
        return User::query()->withTrashed()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
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
            ->paginate(10);
    }
}
