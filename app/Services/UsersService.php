<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersService
{
    public function store(array $data): User
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('Customer');

        event(new Registered($user));

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->fill($data);

        if ($user->isDirty('email')) {
            Log::info('[EMAIL]', [
                'user_id' => $user->id,
                'old_email' => $user->getOriginal('email'),
                'new_email' => $user->email,
            ]);

            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    public function roleUpdate(int $id, string $role): User
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!$user->hasRole($role)) {
            $user->syncRoles($role);

            Log::warning('[ROLE]', [
                'admin_id' => auth()->user()->id,
                'user_id' => $id,
                'role' => $role,
            ]);
        }

        $user->save();

        return $user;
    }

    public function passwordUpdate(int $id, string $password): User
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->password = Hash::make($password);
        $user->save();

        Log::warning('[PASSWORD]', [
            'admin_id' => auth()->user()->id,
            'user_id' => $id,
        ]);

        return $user;
    }

    public function destroy(int $id): void
    {
        $user = User::findOrFail($id);

        $user->delete();

        Log::info('[DELETE]', [
            'user_id' => $user->id,
        ]);
    }

    public function restore(int $id): void
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->restore();

        Log::warning('[RESTORE]', [
            'admin_id' => auth()->user()->id,
            'user_id' => $id,
        ]);
    }

    public function forceDelete(int $id): void
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->forceDelete();

        Log::warning('[FORCE DELETE]', [
            'admin_id' => auth()->user()->id,
            'user_id' => $id,
        ]);
    }

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
                $query->where('users.email', 'like', '%' . $search . '%');
            })
            ->select(
                'users.id',
                'users.email',
                'roles.name as role',
                DB::raw(
                    '(CASE WHEN users.deleted_at IS NULL THEN "Active" ELSE "Inactive" END) AS deleted'
                )
            )
            ->where('users.id', '!=', auth()->user()->id)
            ->orderBy('users.id')
            ->paginate(50);
    }
}
