<?php

namespace App\Http\Controllers\Web\Customers;

use App\Domain\Customers\Contracts\UpdateCustomer;
use App\Domain\Customers\Enums\DocumentType;
use App\Domain\Customers\Services\CustomersService;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminCustomerController extends Controller
{
    /**
     * Show the customers index page for administrator.
     *
     * @param Request          $request
     * @param CustomersService $customersService
     *
     * @return Response
     */
    public function index(Request $request, CustomersService $customersService): Response
    {
        $customers = $customersService->listCustomersToTable($request->get('search'));

        return Inertia::render('Administrator/Customers/Index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Shows the customer update page for administrator.
     *
     * @param User $user
     *
     * @return Response
     */
    public function customerShow(User $user): Response
    {
        return Inertia::render('Administrator/Customers/EditCustomer', [
            'user' => $user->load('customer.city'),
            'document_types' => DocumentType::cases(),
        ]);
    }

    /**
     * Updates the customer.
     *
     * @param CustomerUpdateRequest $request
     * @param User                  $user
     * @param UpdateCustomer        $updateCustomerAction
     *
     * @return RedirectResponse
     */
    public function customerUpdate(CustomerUpdateRequest $request, User $user, UpdateCustomer $updateCustomerAction): RedirectResponse
    {
        $updateCustomerAction->execute($user, $request->validated());

        return redirect()->route('admin.customer.show', $user->getKey());
    }
}
