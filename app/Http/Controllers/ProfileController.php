<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Department;
use App\Services\CustomersService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        Log::info('[DELETE]', [
            'user_id' => $user->id,
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
