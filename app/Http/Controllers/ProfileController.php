<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\Customers\UpdateCustomer;
use App\Contracts\Actions\Users\DeleteUser;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Department;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Shows the user profile.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user()->load('customer.city'),
            'departments' => Department::all(),
        ]);
    }

    /**
     * Updates the user profile.
     *
     * @param CustomerUpdateRequest $request
     * @param UpdateCustomer        $updateCustomerAction
     *
     * @return RedirectResponse
     */
    public function update(CustomerUpdateRequest $request, UpdateCustomer $updateCustomerAction): RedirectResponse
    {
        $updateCustomerAction->execute($request->user(), $request->validated());

        return Redirect::route('profile.edit');
    }

    /**
     * Disables the user.
     *
     * @param Request    $request
     * @param DeleteUser $DeleteUserAction
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, DeleteUser $DeleteUserAction): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $DeleteUserAction->execute($request->user());

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Shows the user order history
     *
     * @param Request $request
     *
     * @return Response
     */
    public function orderHistory(Request $request): Response
    {
        return Inertia::render('Order/OrderHistory', [
            'orders' => $request->user()->load('order.order_detail'),
        ]);
    }
}
