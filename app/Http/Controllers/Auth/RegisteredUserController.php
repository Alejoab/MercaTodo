<?php

namespace App\Http\Controllers\Auth;

use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Department;
use App\Providers\RouteServiceProvider;
use App\Services\CustomersService;
use Illuminate\Http\RedirectResponse;
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
    public function store(CustomerRequest $request, CustomersService $service): RedirectResponse
    {
        $service->store($request->validated());

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
