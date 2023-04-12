<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Users/Index');
    }

    public function userShow($id): Response
    {
        $user = User::withTrashed()->findOrFail($id);
        return Inertia::render('Administrator/Users/EditUser', [
            'user' => User::withTrashed()->findOrFail($id)->load('roles:name'),
            'roles' => Role::all()->pluck('name'),
        ]);
    }

    public function userUpdate(Request $request, $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!$user->hasRole($request->role)) {
            $user->syncRoles($request->role);

            Log::warning('[ROLE]', [
                'admin_id' => auth()->user()->id,
                'user_id' => $id,
                'role' => $request->role,
            ]);
        }

        $user->save();

        return redirect()->route('admin.user.show', $id);
    }

    public function userUpdatePassword(Request $request, $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        Log::warning('[PASSWORD]', [
            'admin_id' => auth()->user()->id,
            'user_id' => $id,
        ]);

        return redirect()->route('admin.user.show', $id);
    }

    public function userDestroy($id): void
    {
        User::withTrashed()->findOrFail($id)->delete();

        Log::warning('[DELETE]', [
            'admin_id' => auth()->user()->id,
            'user_id' => $id,
        ]);
    }

    public function userRestore($id): void
    {
        User::withTrashed()->findOrFail($id)->restore();

        Log::warning('[RESTORE]', [
            'admin_id' => auth()->user()->id,
            'user_id' => $id,
        ]);
    }

    public function userForceDelete(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        User::withTrashed()->findOrFail($id)->forceDelete();

        Log::warning('[FORCE DELETE]', [
            'admin_id' => auth()->user()->id,
            'user_id' => $id,
        ]);

        return Redirect::to(route('admin.users'));
    }

    public function listUsers(Request $request): LengthAwarePaginator
    {
        return User::withTrashed()
            ->join(
                'model_has_roles',
                'users.id',
                '=',
                'model_has_roles.model_id'
            )
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->when($request->input('search'), function ($query, $search) {
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
            ->paginate(50);
    }
}
