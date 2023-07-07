<?php

namespace App\Http\Controllers\Administrator;

use App\Contracts\Actions\Users\DeleteUser;
use App\Contracts\Actions\Users\ForceDeleteUser;
use App\Contracts\Actions\Users\RestoreUser;
use App\Contracts\Actions\Users\UpdateUserPassword;
use App\Contracts\Actions\Users\UpdateUserRole;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Users\UsersService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    /**
     * Show the users list.
     *
     * @param Request      $request
     * @param UsersService $service
     *
     * @return Response
     */
    public function index(Request $request, UsersService $service): Response
    {
        $users = $service->listUsersToTable($request->get('search'));

        return Inertia::render('Administrator/Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Shows the user page.
     *
     * @param User $user
     *
     * @return Response
     */
    public function userShow(User $user): Response
    {
        return Inertia::render('Administrator/Users/EditUser', [
            'user' => $user->load(['roles:name', 'permissions:name']),
            'roles' => RoleEnum::cases(),
            'permissions_view' => PermissionEnum::cases(),
        ]);
    }

    /**
     * Updates a user.
     *
     * @param Request        $request
     * @param User           $user
     * @param UpdateUserRole $updateRoleAction
     *
     * @return RedirectResponse
     */
    public function userUpdate(Request $request, User $user, UpdateUserRole $updateRoleAction): RedirectResponse
    {
        $updateRoleAction->execute($user, $request['role'], $request['permissions']);

        return redirect()->route('admin.user.show', $user->getKey());
    }

    /**
     * Updates a user password.
     *
     * @param Request            $request
     * @param User               $user
     * @param UpdateUserPassword $action
     *
     * @return RedirectResponse
     */
    public function userUpdatePassword(Request $request, User $user, UpdateUserPassword $action): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $action->execute($user, $request['password']);

        return redirect()->route('admin.user.show', $user->getKey());
    }

    /**
     * Disables a user.
     *
     * @param User       $user
     * @param DeleteUser $action
     *
     * @return void
     */
    public function userDestroy(User $user, DeleteUser $action): void
    {
        $action->execute($user);
    }

    /**
     * Restores a user.
     *
     * @param User        $user
     * @param RestoreUser $action
     *
     * @return void
     */
    public function userRestore(User $user, RestoreUser $action): void
    {
        $action->execute($user);
    }

    /**
     * Force deletes a user.
     *
     * @param Request         $request
     * @param User            $user
     * @param ForceDeleteUser $action
     *
     * @return RedirectResponse
     */
    public function userForceDelete(Request $request, User $user, ForceDeleteUser $action): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $action->execute($user);

        return Redirect::to(route('admin.users'));
    }
}
