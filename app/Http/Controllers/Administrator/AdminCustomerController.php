<?php

namespace App\Http\Controllers\Administrator;

use App\Contracts\Actions\Customers\UpdateCustomer;
use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\User;
use App\Services\Customers\CustomersService;
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

    public function customerShow(User $user): Response
    {
        return Inertia::render('Administrator/Customers/EditCustomer', [
            'user' => $user->load('customer.city'),
            'document_types' => DocumentType::cases(),
        ]);
    }

    public function listCustomers(Request $request, CustomersService $service): LengthAwarePaginator
    {
        return $service->listCustomersToTable($request->get('search'));
    }

    public function customerUpdate(CustomerUpdateRequest $request, User $user, UpdateCustomer $action): RedirectResponse
    {
        $action->execute($user, $request->validated());

        return redirect()->route('admin.customer.show', $user->getKey());
    }
}
