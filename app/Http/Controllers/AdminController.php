<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use App\Models\Department;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            ->join('model_has_roles', 'id', '=', 'model_id' )
            ->select('id', 'name', 'surname', 'document_type', 'document', 'email', 'name', 'deleted_at', 'role_id')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orwhere('surname', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                ;
            })
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
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($id)],
            'phone' => ['nullable', 'string', 'digits:10'],
            'role' => ['string'],
        ]);

        $user->fill($request->only('name', 'surname', 'document_type', 'document', 'email', 'phone'));

        $rol = $user->getRoleNames();

        if (empty($rol->toArray())) {
            Log::warning('The administrator '.auth()->user()->id.' has assigned the role '.$request->role.' to the user '.$id);
            $user->assignRole($request->role);
        } elseif ($rol[0] != $request->role) {
            Log::warning('The administrator '.auth()->user()->id.' has assigned the role '.$request->role.' to the user '.$id);
            $user->removeRole($rol[0]);
            $user->assignRole($request->role);
        }

        if ($user->isDirty('email')) {
            Log::warning('The administrator '.auth()->user()->id.' has changed the email of the user '.$id);
            $user->email_verified_at = null;
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

        Log::warning('The administrator '.auth()->user()->id.' has changed the password of the user '.$id);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.user.show', $id);
    }

    public function userDestroy($id): void
    {
        Log::warning('The administrator '.auth()->user()->id.' has disabled the user '.$id);
        User::withTrashed()->findOrFail($id)->delete();
    }

    public function userRestore($id): void
    {
        Log::warning('The administrator '.auth()->user()->id.' has enabled the user '.$id);
        User::withTrashed()->findOrFail($id)->restore();
    }

    public function userForceDelete(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        User::withTrashed()->findOrFail($id)->forceDelete();

        Log::warning('The administrator '.auth()->user()->id.' has deleted the user '.$id);

        return Redirect::to(route('admin.users'));
    }
}
