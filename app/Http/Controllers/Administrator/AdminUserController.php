<?php

namespace App\Http\Controllers\Administrator;

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

    public function userUpdate(Request $request, int $id, UsersService $service): RedirectResponse
    {
        $service->roleUpdate($id, $request['role']);

        return redirect()->route('admin.user.show', $id);
    }

    public function userUpdatePassword(Request $request, int $id, UsersService $service): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $service->passwordUpdate($id, $request['password']);

        return redirect()->route('admin.user.show', $id);
    }

    public function userDestroy(int $id, UsersService $service): void
    {
        $service->destroy($id);
    }

    public function userRestore(int $id, UsersService $service): void
    {
        $service->restore($id);
    }

    public function userForceDelete(Request $request, int $id, UsersService $service): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $service->forceDelete($id);

        return Redirect::to(route('admin.users'));
    }

    public function listUsers(Request $request, UsersService $service): LengthAwarePaginator
    {
        return $service->listUsersToTable($request->get('search'));
    }
}
