<?php

namespace App\Http\Controllers\Administrator;

use App\Contracts\Actions\Users\DeleteUser;
use App\Contracts\Actions\Users\ForceDeleteUser;
use App\Contracts\Actions\Users\RestoreUser;
use App\Contracts\Actions\Users\UpdateUserPassword;
use App\Contracts\Actions\Users\UpdateUserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UsersService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return Inertia::render('Administrator/Users/EditUser', [
            'user' => User::withTrashed()->findOrFail($id)->load('roles:name'),
            'roles' => Role::all()->pluck('name'),
        ]);
    }

    public function userUpdate(Request $request, int $id, UpdateUserRole $action): RedirectResponse
    {
        $action->execute($id, $request['role']);

        return redirect()->route('admin.user.show', $id);
    }

    public function userUpdatePassword(Request $request, int $id, UpdateUserPassword $action): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $action->execute($id, $request['password']);

        return redirect()->route('admin.user.show', $id);
    }

    public function userDestroy(int $id, DeleteUser $action): void
    {
        $action->execute($id);
    }

    public function userRestore(int $id, RestoreUser $action): void
    {
        $action->execute($id);
    }

    public function userForceDelete(Request $request, int $id, ForceDeleteUser $action): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $action->execute($id);

        return Redirect::to(route('admin.users'));
    }

    public function listUsers(Request $request, UsersService $service): LengthAwarePaginator
    {
        return $service->listUsersToTable($request->get('search'));
    }
}
