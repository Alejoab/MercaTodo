<?php

namespace App\Services\Users;

use App\Domain\Users\Models\User;
use App\QueryBuilders\UserQueryBuilder;
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
        /**
         * @var UserQueryBuilder $users
         */
        $users = User::query()->withTrashed()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id');

        return $users
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
