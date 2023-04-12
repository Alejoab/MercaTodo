<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Users/Index');
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
