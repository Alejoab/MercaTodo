<?php

namespace App\Http\Controllers\Administrator;

use App\Contracts\Actions\Customers\UpdateCustomer;
use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Department;
use App\Models\User;
use App\Services\CustomersService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminCustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Customers/Index');
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

    public function listCustomers(Request $request, CustomersService $service): LengthAwarePaginator
    {
        return $service->listCustomersToTable($request->get('search'));
    }

    public function customerUpdate(CustomerUpdateRequest $request, int $id, UpdateCustomer $action): RedirectResponse
    {
        $action->execute($id, $request->validated());

        return redirect()->route('admin.customer.show', $id);
    }
}
