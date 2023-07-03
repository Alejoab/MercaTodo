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
    /**
     * Show the customers index page for administrator.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Administrator/Customers/Index');
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
     * Lists the customers for the administrator.
     *
     * @param Request          $request
     * @param CustomersService $customersService
     *
     * @return LengthAwarePaginator
     */
    public function listCustomers(Request $request, CustomersService $customersService): LengthAwarePaginator
    {
        return $customersService->listCustomersToTable($request->get('search'));
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
