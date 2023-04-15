<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

class AdminCustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Customers/Index');
    }

    public function listCustomers(Request $request): LengthAwarePaginator
    {
        return User::withTrashed()
            ->join(
                'customers',
                'customers.user_id',
                '=',
                'users.id'
            )
            ->join(
                'cities',
                'customers.city_id',
                '=',
                'cities.id'
            )
            ->join(
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
                'users.id',
                'users.email',
                'customers.name',
                'customers.surname',
                'customers.document_type',
                'customers.document',
                'customers.phone',
                'cities.name as city',
                'departments.name as department',
                'customers.address'
            )
            ->get
            ->orderBy('users.id')
            ->paginate(50);
    }

    public function customerShow($id): Response
    {
        $user = User::withTrashed()->findOrFail($id)->load('customer.city');
        return Inertia::render('Administrator/Customers/EditCustomer', [
            'user' => $user,
            'document_types' => DocumentType::cases(),
            'departments' => Department::all(),
        ]);
    }

    public function customerUpdate(CustomerUpdateRequest $request, $id): RedirectResponse {
        $user = User::withTrashed()->findOrFail($id);

        $user->customer->fill($request->validated());

        $user->customer->save();

        return redirect()->route('admin.customer.show', $id);
    }
}
