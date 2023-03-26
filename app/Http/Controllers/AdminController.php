<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use App\Models\Department;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        return User::withTrashed()->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orwhere('surname', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                ;
            })->paginate(50);
    }

    public function userShow($id): Response
    {
        $user = User::withTrashed()->find($id)->load('city');
        return Inertia::render('Administrator/EditUser', [
            'user' => $user,
            'role' => $user->getRoleNames(),
            'documentTypes' => DocumentType::cases(),
            'departments' => Department::all(),
        ]);
    }

    public function userUpdate(Request $request, $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);

        $request->validate([
            'name' => ['string', 'max:255'],
            'surname' => ['string', 'max:255'],
            'document_type' => [new Enum(DocumentType::class)],
            'document' => ['string', 'digits_between:8,11', Rule::unique(User::class)->ignore($id)],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($id)],
            'phone' => ['string', 'digits:10'],
            'role' => ['boolean']
        ]);

        $user->fill($request->only('name', 'surname', 'document_type', 'document', 'email', 'phone'));
        $user->save();

        $role = $user->getRoleNames();

        if (!$role->isEmpty() != $request->role) {
            if ($request->role) {
                $user->assignRole('Administrator');
            } else {
                $user->removeRole('Administrator');
            }
        }

        return redirect()->route('admin.user.show', $id);
    }

    public function userUpdateAddress(Request $request, $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);

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
        $user = User::withTrashed()->find($id);

        $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.user.show', $id);
    }

    public function userDestroy($id): void
    {
        User::find($id)->delete();
    }

    public function userRestore($id): void
    {
        User::withTrashed()->where('id', $id)->restore();
    }

    public function userForceDelete(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        User::withTrashed()->find($id)->forceDelete();

        return Redirect::to(route('admin.users'));
    }
}
