<?php

namespace App\Http\Controllers\Web\Users;

use App\Domain\Customers\Contracts\UpdateCustomer;
use App\Domain\Customers\Models\Department;
use App\Domain\Users\Contracts\DeleteUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user()->load('customer.city'),
            'departments' => Department::all(),
        ]);
    }

    public function update(CustomerUpdateRequest $request, UpdateCustomer $updateCustomerAction): RedirectResponse
    {
        $updateCustomerAction->execute($request->user(), $request->validated());

        return Redirect::route('profile.edit');
    }

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

    public function orderHistory(Request $request): Response
    {
        return Inertia::render('Order/OrderHistory', [
            'orders' => $request->user()->load('order.order_detail'),
        ]);
    }
}
