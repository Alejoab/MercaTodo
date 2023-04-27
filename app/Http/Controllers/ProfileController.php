<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\Users\DeleteUser;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Department;
use App\Services\CustomersService;
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
     * Display the user's profile form.
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
     * Update the user's profile information.
     */
    public function update(CustomerUpdateRequest $request, CustomersService $service): RedirectResponse
    {
        $service->update($request->user()->customer->id, $request->validated());

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request, DeleteUser $action): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $action->execute($request->user()->id);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
