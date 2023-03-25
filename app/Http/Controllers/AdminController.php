<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
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
}
