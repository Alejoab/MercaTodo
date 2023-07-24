<?php

namespace App\Http\Controllers\Web\Users;

use App\Domain\Users\Contracts\DeleteUser;
use App\Domain\Users\Contracts\ForceDeleteUser;
use App\Domain\Users\Contracts\RestoreUser;
use App\Domain\Users\Contracts\UpdateUserPassword;
use App\Domain\Users\Contracts\UpdateUserRole;
use App\Domain\Users\Enums\PermissionEnum;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use App\Domain\Users\Services\UsersService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRoleUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(Request $request, UsersService $service): Response
    {
        $users = $service->listUsersToTable($request->get('search'));

        return Inertia::render('Administrator/Users/Index', [
            'users' => $users,
        ]);
    }

    public function userShow(User $user): Response
    {
        return Inertia::render('Administrator/Users/EditUser', [
            'user' => $user->load(['roles:name', 'permissions:name']),
            'roles' => RoleEnum::cases(),
            'permissions_view' => PermissionEnum::cases(),
        ]);
    }

    public function userUpdate(UserRoleUpdateRequest $request, User $user, UpdateUserRole $updateRoleAction): RedirectResponse
    {
        $updateRoleAction->execute($user, $request['role'], $request['permissions']);

        return redirect()->route('admin.user.show', $user->getKey());
    }

    public function userUpdatePassword(Request $request, User $user, UpdateUserPassword $action): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $action->execute($user, $request['password']);

        return redirect()->route('admin.user.show', $user->getKey());
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
}
