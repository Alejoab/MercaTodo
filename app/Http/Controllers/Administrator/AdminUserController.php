<?php

namespace App\Http\Controllers\Administrator;

use App\Contracts\Actions\Users\DeleteUser;
use App\Contracts\Actions\Users\ForceDeleteUser;
use App\Contracts\Actions\Users\RestoreUser;
use App\Contracts\Actions\Users\UpdateUserPassword;
use App\Contracts\Actions\Users\UpdateUserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Users\UsersService;
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

    public function userShow(User $user): Response
    {
        return Inertia::render('Administrator/Users/EditUser', [
            'user' => $user->load('roles:name'),
            'roles' => Role::all()->pluck('name'),
        ]);
    }

    public function userUpdate(Request $request, User $user, UpdateUserRole $action): RedirectResponse
    {
        $action->execute($user, $request['role']);

        return redirect()->route('admin.user.show', $user->id);
    }

    public function userUpdatePassword(Request $request, User $user, UpdateUserPassword $action): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $action->execute($user, $request['password']);

        return redirect()->route('admin.user.show', $user->id);
    }

    public function userDestroy(User $user, DeleteUser $action): void
    {
        $action->execute($user);
    }

    public function userRestore(User $user, RestoreUser $action): void
    {
        $action->execute($user);
    }

    public function userForceDelete(Request $request, User $user, ForceDeleteUser $action): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $action->execute($user);

        return Redirect::to(route('admin.users'));
    }

    public function listUsers(Request $request, UsersService $service): LengthAwarePaginator
    {
        return $service->listUsersToTable($request->get('search'));
    }
}
