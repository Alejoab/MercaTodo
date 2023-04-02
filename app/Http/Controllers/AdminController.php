<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use App\Models\Department;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Home');
    }

    public function users(): Response
    {
        return Inertia::render('Administrator/Users');
    }

    public function listUsers(Request $request): LengthAwarePaginator
    {
        return User::withTrashed()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.surname', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%');
            })
            ->select('users.id', 'users.name', 'users.surname', 'users.document_type', 'users.document', 'users.email', 'roles.name as role', DB::raw('(CASE WHEN users.deleted_at IS NULL THEN "Active" ELSE "Inactive" END) AS deleted'))
            ->paginate(50);
    }

    public function userShow($id): Response
    {
        $user = User::withTrashed()->findOrFail($id)->load('city');
        return Inertia::render('Administrator/EditUser', [
            'user' => $user,
            'role' => $user->getRoleNames(),
            'document_types' => DocumentType::cases(),
            'departments' => Department::all(),
        ]);
    }

    public function userUpdate(Request $request, $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => ['string', 'max:255'],
            'surname' => ['string', 'max:255'],
            'document_type' => [new Enum(DocumentType::class)],
            'document' => ['string', 'digits_between:8,11', Rule::unique(User::class)->ignore($id)],
            'phone' => ['nullable', 'string', 'digits:10'],
            'role' => ['string'],
        ]);

        $user->fill($request->only('name', 'surname', 'document_type', 'document', 'phone'));

        $rol = $user->getRoleNames();

        if (empty($rol->toArray())) {
            $user->assignRole($request->role);

            Log::warning('[ROLE]', [
                'admin_id' => auth()->user()->id,
                'user_id' => $id,
                'role' => $request->role,
            ]);

        } elseif ($rol[0] != $request->role) {
            $user->removeRole($rol[0]);
            $user->assignRole($request->role);

            Log::warning('[ROLE]', [
                'admin_id' => auth()->user()->id,
                'user_id' => $id,
                'role' => $request->role,
            ]);
        }

        $user->save();

        return redirect()->route('admin.user.show', $id);
    }

    public function userUpdateAddress(Request $request, $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        $request->validate([
            'city_id' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $user->city_id = $request->city_id;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('admin.user.show', $id);
    }

    public function userUpdatePassword(Request $request, $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
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
}
