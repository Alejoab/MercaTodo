<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminCustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Customers/Index');
    }

    public function listCustomers(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        return User::withTrashed()
            ->join(
                'customers',
                'users.customer_id',
                '=',
                'customers.id'
            )->join('cities', 'customers.city_id', '=', 'cities.id')->join(
                'departments',
                'cities.department_id',
                '=',
                'departments.id'
            )
            ->when($request->input('search'), function ($query, $search) {
                $query->where('customers.name', 'like', '%' . $search . '%')
                    ->orWhere('customers.document', 'like', '%' . $search . '%')
                    ->orWhere('customers.surname', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%');
            })
            ->select(
                'users.id as user_id',
                'users.email as user_email',
                'customers.id as id',
                'customers.name',
                'customers.surname',
                'customers.document_type',
                'customers.document',
                'users.email',
                'customers.phone',
                'cities.name as city',
                'departments.name as department',
                DB::raw(
                    '(CASE WHEN users.deleted_at IS NULL THEN "Active" ELSE "Inactive" END) AS status'
                )
            )
            ->paginate(50);
    }
}
