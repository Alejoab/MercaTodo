<?php

namespace App\Http\Controllers\Web\Auth;

use App\Domain\Customers\Contracts\CreateCustomer;
use App\Domain\Customers\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Support\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     */
    public function store(CustomerRequest $request, CreateCustomer $action): RedirectResponse
    {
        $customer = $action->execute($request->validated());

        Auth::login($customer->user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'document_types' => DocumentType::cases(),
        ]);
    }
}
