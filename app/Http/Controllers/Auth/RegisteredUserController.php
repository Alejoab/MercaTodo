<?php

namespace App\Http\Controllers\Auth;

use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $customer = Customer::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'document' => $validated['document'],
            'document_type' => $validated['document_type'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city_id' => $validated['city_id'],
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'customer_id' => $customer->id,
        ]);

        $user->assignRole('Customer');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'document_types' => DocumentType::cases(),
            'departments' => Department::all(),
        ]);
    }
}
