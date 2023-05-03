<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UsersService
{
    public function listUsersToTable(string|null $search): LengthAwarePaginator
    {
        return User::withTrashed()
            ->join(
                'model_has_roles',
                'users.id',
                '=',
                'model_has_roles.model_id'
            )
            ->join(
                'roles',
                'roles.id',
                '=',
                'model_has_roles.role_id'
            )
            ->when($search, function ($query, $search) {
                $query->where('users.email', 'like', '%'.$search.'%');
            })
            ->select(
                ['users.id',
                'users.email',
                'roles.name as role',
                'users.deleted_at as deleted']
            )
            ->where('users.id', '!=', auth()->user()->getAuthIdentifier())
            ->orderBy('users.id')
            ->paginate(50);
    }
}
